<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeController extends Controller
{
    public function ViewEmployee(){
        $employes = Employe::all();
         // return employeResource::collection(Inscriptionemploye::orderBy('created_at','desc')->where('archived_at',null)->get());
         // return Inscriptionemploye::orderBy('created_at','desc')->where('archived_at',null)->get();
 
        return response()->json($employes);
    }


    public function CreateEmployee(Request $request){

        try {
            // $file1 = $request->file('profil');
            // $name1 = time().$file1->getClientOriginalName();

            Employe::create([
                
                'nameEmp'=> $request->input('nameEmp'),
                'surnameEmp'=> $request->input('surnameEmp'),
                'email'=> $request->input('email'),
                'birthday'=> $request->input('birthday'),
                'sexe'=> $request->input('sexe'),
                'Tel'=> $request->input('Tel'),
                'poste'=> $request->input('poste'),
                'salaire'=> $request->input('salaire'),
                // 'profil'=>$name1,
            ]);

            // $file1->move('uploadImage', $name1); //move regarde directement dans public

            return response()->json([
                'message'=>'true'
            ]);
        } catch (\Throwable $e) {
            // Imprimez le message d'erreur dans les logs
            Log::error($e);

            // Retournez une réponse JSON avec un message d'erreur
            return response()->json([
                'error' => $e->getMessage() // Vous pouvez également utiliser 'message' ici, selon vos besoins
            ]);
        }

    }


    public function DeleteEmployee(string $id){

        // $employe = Inscriptionemploye::find($id);
        // $employe = employeResource::collection(Inscriptionemploye::withTrashed()->find($id));
        $employe = Employe::find($id)->delete();

        if (!$employe) {
            return response()->json([
                'message'=>'employe non trouvé'
            ]);
        }

        if (!$employe ->trashed()) {
            return response()->json([
                'message'=>"Cet employe n'est pas dans la corbeille"
            ]);
        }
        $employe->forceDelete();

        return response()->json([
            'message'=>'employe supprime definitivement avec succes',
        ]);
    }

    public function ShowInfosEmployeeEdit(string $id)
    {
        $employe=Employe::findOrFail($id);
        // $employe->formation->nomForm;
        return response()->json($employe);

    }

    //fonction pour editer les informations affichees
    public function EditerEmployee(Request $request, string $id)
    {
        try {
            $employe=Employe::find($id);


            $employe->update([
                'nameEmp'=> $request->input('nameEmp'),
                'surnameEmp'=> $request->input('surnameEmp'),
                'email'=> $request->input('email'),
                'birthday'=> $request->input('birthday'),
                'sexe'=> $request->input('sexe'),
                'Tel'=> $request->input('Tel'),
                'poste'=> $request->input('poste'),
                'salaire'=> $request->input('salaire'),
            ]);

        return response()->json([
            'message'=>true,
        ]);
        } catch (\Throwable $th) {
            return response()->json($th);
        }


    }

    //supprimer plusieurs employes a la fois

    public function deleteSelected(Request $request)
    {
        $etudiants = Employe::find($request->data);
        foreach($etudiants as $etudiant){
            $etudiant->delete();
        }

        return response()->json(true);
    }

    public function EmployeePerMonth()
    {
        $monthsData = Employe::select(DB::raw('COUNT(*) as count'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    // ->where('inscription_etudiants.archived_at',null)
                    // ->where('inscription_etudiants.deleted_at',null)
                    ->get();
        // $monthsData = DB::table('inscription_etudiants')
                    // ->join('formations', 'formations.id', '=', 'inscription_etudiants.formation_id')
                    // // ->select(DB::raw('MONTH(inscription_etudiants.created_at) as month'), DB::raw('COUNT(*) as count'),DB::raw('YEAR(inscription_etudiants.created_at) as year'))
                    // ->select(DB::raw('MONTH(inscription_etudiants.created_at) as month'), DB::raw('COUNT(*) as count'),DB::raw('YEAR(inscription_etudiants.created_at) as year'))
                    // ->groupBy(DB::raw('MONTH(inscription_etudiants.created_at)'))
                    // ->get();

            $result = [];

            // Fill array with 0 count for all months
            for ($i = 1; $i <= 12; $i++) {
                $result[$i] = ['month' => $i, 'count' => 0];
            }

            // Update counts for months with data
            foreach ($monthsData as $data) {
                $result[$data->month] = ['month' => $data->month, 'count' => $data->count];
            }

            // return response()->json(['studentsPerMonth' => array_values($result)]);

        // return response()->json(['studentsPerMonth' => $studentsPerMonth]);
        return response()->json(['employeePerMonth' => array_values($result)]);
    }



}
