<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Setting;
use App\Imports\EmployeesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
// Importación necesaria para el QR
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EmployeeController extends Controller
{
    /**
     * Muestra la lista de personal con paginación.
     */
    public function index()
    {
        $employees = Employee::latest()->paginate(10);
        $settings = Setting::pluck('value', 'key')->all();
        
        return view('employees.index', compact('employees', 'settings'));
    }

    /**
     * Muestra el formulario de registro.
     */
    public function create()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('employees.create', compact('settings'));
    }

    /**
     * Almacena un nuevo empleado y procesa su fotografía.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|unique:employees,cedula',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cargo' => 'required',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('employees/photos', 'public');
            $data['foto'] = $path;
        }

        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('success', 'Personal registrado correctamente.');
    }

    /**
     * Muestra el formulario para editar el personal.
     */
    public function edit(Employee $employee)
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('employees.edit', compact('employee', 'settings'));
    }

    /**
     * Actualiza el registro en la base de datos.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'cedula' => 'required|unique:employees,cedula,' . $employee->id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cargo' => 'required',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($employee->foto) {
                Storage::disk('public')->delete($employee->foto);
            }
            $path = $request->file('foto')->store('employees/photos', 'public');
            $data['foto'] = $path;
        }

        $employee->update($data);

        return redirect()->route('employees.index')
            ->with('success', 'Personal actualizado con éxito.');
    }

    /**
     * Muestra la ficha individual con el Código QR generado.
     */
    public function show(Employee $employee)
    {
        $settings = Setting::pluck('value', 'key')->all();

        // Generar QR que apunta a la ruta 'show' de este empleado específico
        $qrCode = QrCode::size(200)
            ->margin(1)
            ->color(0, 0, 0)
            ->generate(route('employees.show', $employee));

        return view('employees.show', compact('employee', 'settings', 'qrCode'));
    }

    /**
     * Importa personal desde un archivo Excel/CSV.
     */
    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            Excel::import(new EmployeesImport, $request->file('file'));
            
            return redirect()->route('employees.index')
                ->with('success', '¡Personal importado exitosamente!');

        } catch (\Exception $e) {
            Log::error('Error de importación: ' . $e->getMessage());

            return redirect()->route('employees.index')
                ->with('error', 'Error en la importación: Verifique el formato del archivo.');
        }
    }

    /**
     * Elimina un empleado y su fotografía.
     */
    public function destroy(Employee $employee)
    {
        if ($employee->foto) {
            Storage::disk('public')->delete($employee->foto);
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Registro eliminado correctamente.');
    }
}