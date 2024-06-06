<?php

namespace App\Http\Controllers;

use App\Models\Abscence;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbscenceController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$abscences=Abscence::all();

        $abscences = DB::table('abscences')
            ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
            ->join('formations', 'formations.id', '=', 'abscences.formation_id')
            ->join('cours', 'cours.id', '=', 'abscences.cour_id')
            ->select('cours.nomCours', 'abscences.id','abscences.etudiant_id','abscences.formation_id','abscences.cour_id','abscences.dateAbs','abscences.nbreHeureAbs','abscences.typeAbs','abscences.motifAbs','abscences.supprimer','abscences.archiver','inscription_etudiants.nomEtud','inscription_etudiants.prenomEtud','formations.nomForm')
            ->where('abscences.supprimer',0)
            ->Where('abscences.archiver',0)
            ->orderBy('id','desc')
            ->get();

        return response()->json($abscences);

    }

       /**
         * Display a listing of the resource.
         */
        public function absArchiver()
        {
            //$abscences=Abscence::all();

            $abscences = DB::table('abscences')
                ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
                ->join('formations', 'formations.id', '=', 'abscences.formation_id')
                ->join('cours', 'cours.id', '=', 'abscences.cour_id')
                ->select('cours.nomCours', 'abscences.id','abscences.etudiant_id','inscription_etudiants.prenomEtud','abscences.formation_id','abscences.cour_id','abscences.dateAbs','abscences.nbreHeureAbs','abscences.typeAbs','abscences.motifAbs','abscences.supprimer','abscences.archiver','inscription_etudiants.nomEtud','formations.nomForm')
                ->Where('abscences.archiver',1)
                ->where('abscences.supprimer',0)
                ->orderBy('id','desc')
                ->get();

            return response()->json($abscences);

        }

        /**
                 * Display a listing of the resource.
                 */
                public function absSupprimer()
                {
                    //$abscences=Abscence::all();

                    $abscences = DB::table('abscences')
                        ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
                        ->join('formations', 'formations.id', '=', 'abscences.formation_id')
                        ->join('cours', 'cours.id', '=', 'abscences.cour_id')
                        ->select('cours.nomCours', 'abscences.id','abscences.etudiant_id','inscription_etudiants.prenomEtud','abscences.formation_id','abscences.cour_id','abscences.dateAbs','abscences.nbreHeureAbs','abscences.typeAbs','abscences.motifAbs','abscences.supprimer','abscences.archiver','inscription_etudiants.nomEtud','formations.nomForm')
                        ->Where('abscences.supprimer',1)
                        ->Where('abscences.archiver',0)
                        ->orderBy('id','desc')
                        ->get();

                    return response()->json($abscences);

                }

         /**
                         * Display a listing of the resource.
                         */
                        public function seeAbscence(string $id)
                        {
                            //$abscences=Abscence::all();

                          $abscence=Abscence::find($id)
                                  ->get();

//                                   return response()->json([
//                                       'message'=>true
//                                   ]);

                            return response()->json($abscence);

                        }

    // public function index()
    // {
    //     return AbscenceResource::collection(Abscence::all());
    // }

        /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

      public function store(Request $request){
            try {
                Abscence::create([
                    'dateAbs'=> $request->input('dateAbs'),
                    'motifAbs'=> $request->input('motifAbs'),
                    'typeAbs'=> $request->input('typeAbs'),
                    'nbreHeureAbs'=> $request->input('nbreHeureAbs'),
                    'formation_id'=> $request->input('formation_id'),
                    'archiver'=> 0,
                     'supprimer'=> 0,
                    'etudiant_id'=> $request->input('etudiant_id'),
                    'cour_id'=> $request->input('cour_id'),
                ]);
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $abscence = DB::table('abscences')
                    ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
                    ->join('formations', 'formations.id', '=', 'abscences.formation_id')
                    ->join('cours', 'cours.id', '=', 'abscences.cour_id')
                    ->select('cours.nomCours', 'abscences.id','abscences.etudiant_id','abscences.formation_id','abscences.cour_id','abscences.dateAbs','abscences.nbreHeureAbs','abscences.typeAbs','abscences.motifAbs','abscences.supprimer','abscences.archiver','inscription_etudiants.nomEtud','inscription_etudiants.created_at','inscription_etudiants.prenomEtud','formations.nomForm')
                    ->where('abscences.id',$id)
                    ->first();

         //$abscence=Abscence::find($id);


         return response()->json($abscence);
     }

     /**
     * Display the specified absence formations.
     */
     public function absenceForm(string $nomForm)
     {

        $abscence = DB::table('abscences')
                    ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
                    ->join('formations', 'formations.id', '=', 'abscences.formation_id')
                    ->join('cours', 'cours.id', '=', 'abscences.cour_id')
                    ->select('abscences.id','abscences.etudiant_id','abscences.formation_id','abscences.cour_id','abscences.dateAbs','abscences.nbreHeureAbs','abscences.typeAbs','abscences.motifAbs','abscences.supprimer','abscences.archiver','inscription_etudiants.nomEtud','inscription_etudiants.created_at','inscription_etudiants.prenomEtud','formations.nomForm','cours.nomCours')
                    ->where('formations.nomForm',$nomForm)
                    ->get();

             return response()->json($abscence);
     }

     /**
     * Display the specified absence formations.
     */
     public function detailsAbsenceEtud(string $nomForm,string $id)
     {
        try {
            $abscence = DB::table('abscences')
                ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
                ->join('formations', 'formations.id', '=', 'abscences.formation_id')
                ->join('cours', 'cours.id', '=', 'abscences.cour_id')
                ->select('abscences.id','abscences.etudiant_id','abscences.formation_id','abscences.cour_id','abscences.dateAbs','abscences.nbreHeureAbs','abscences.typeAbs','abscences.motifAbs','abscences.supprimer','abscences.archiver','inscription_etudiants.nomEtud','inscription_etudiants.created_at','inscription_etudiants.prenomEtud','formations.nomForm','cours.nomCours')
                ->where('abscences.etudiant_id',$id)
                ->where('formations.nomForm',$nomForm)
                ->orderBy('abscences.dateAbs','desc')
                ->get();
            return response()->json($abscence);
        } catch (\Throwable $e) {
            return response()->json([
                'message'=>$e
            ]);
        }
     }

    public function AbsenceParFiliere()
    {
        try {
            $absence = DB::table('formations')
                ->leftJoin('abscences', function ($join) {
                    $join->on('formations.id', '=', 'abscences.formation_id');
                })
                ->leftJoin('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
                ->leftJoin('cours', 'cours.id', '=', 'abscences.cour_id')
                ->select(DB::raw('formations.id, formations.nomForm, COALESCE(SUM(abscences.nbreHeureAbs), 0) as nombre'))
                ->groupBy('formations.id', 'formations.nomForm')
                ->get();

            return response()->json($absence);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }


    /**
         * Display the specified absence formations.
         */
    public function selectedCours(string $selectedCoursId)
    {
            $abscences = DB::table('abscences')
                ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
                ->join('formations', 'formations.id', '=', 'abscences.formation_id')
                ->join('cours', 'cours.id', '=', 'abscences.cour_id')
                ->select(
                    'inscription_etudiants.nomEtud',
                    'inscription_etudiants.prenomEtud',
                    'cours.nomCours',
                    'formations.nomForm',
                    'abscences.etudiant_id'
                )
                ->where('abscences.cour_id', $selectedCoursId)
                ->get();

            return response()->json($abscences);
    }


    /**
     * Permet de rechercher un produit dont le nom est parmi les caracteres envoyés
     */
    public function edit(string $id)
    {

        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        try {
            $abscences=Abscence::find($id);
            if (!$abscences) {
                return response()->json(['message' => 'Abscence non trouvée'], 404);
            }
            $abscences->update([
                'dateAbs'=> $request->input('dateAbsc'),
                'motifAbs'=> $request->input('motifAbs'),
                'typeAbs'=> $request->input('typeAbs'),
                'nbreHeureAbs'=> $request->input('nbreHeureAbs'),
            ]);
            return response()->json([
                               'message'=>'true'
                           ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message'=>$e
            ]);
        }

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $abscence=Abscence::find($id);
        $abscence->delete();

        return response()->json([
            'message'=>true
        ]);
    }

   // Marquer une absence comme supprimée
    public function supprimer($id)
    {
        $abscence = Abscence::find($id);
        if (!$abscence) {
            return response()->json(['message' => 'Abscence non trouvée'], 404);
        }

        $abscence->supprimer = 1; // Marquer comme supprimée
        $abscence->archiver = 0; // Marquer comme supprimée
        $abscence->save();

        return response()->json(['message' => 'Abscence supprimée']);
    }

    // Archiver une absence
    public function archiver($id)
    {
        $abscence = Abscence::find($id);
        if (!$abscence) {
            return response()->json(['message' => 'Abscence non trouvée'], 404);
        }

        $abscence->archiver = 1; // Marquer comme archivée
        $abscence->supprimer = 0; // Marquer comme supprimée
        $abscence->save();

        return response()->json(['message' => 'Abscence archivée']);
    }

     // Archiver une absence
    public function archiverRestaurer($id)
    {
        $abscence = Abscence::find($id);
        if (!$abscence) {
            return response()->json(['message' => 'Abscence non trouvée'], 404);
        }

        $abscence->archiver = 0; // Marquer comme non archivée
        $abscence->supprimer = 0; // Marquer comme non supprimée
        $abscence->save();

        return response()->json(['message' => 'Abscence archivée']);
    }

    public function deleteSelected(Request $request)
    {

    $datas = $request->input('data');

        foreach ($datas as $data) {
            $abscence = Abscence::find($data);
            if (!$abscence) {
                return response()->json(['message' => 'Abscence non trouvée'], 404);
            }
            $abscence->delete();
        }
        return response()->json(['message' => 'Abscences supprimer definitivement']);
    }

    public function restauresSelected(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $abscence = Abscence::find($id);
            if (!$abscence) {
                return response()->json(['message' => 'Abscence non trouvée'], 404);
            }
            $abscence->archiver = 1;
            $abscence->supprimer = 0;
            $abscence->save();
        }
        return response()->json(['message' => 'Abscences restaurées']);
    }

    public function restaureSelected(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $abscence = Abscence::find($id);
            if (!$abscence) {
                return response()->json(['message' => 'Abscence non trouvée'], 404);
            }
            $abscence->archiver = 0;
            $abscence->supprimer = 0;
            $abscence->save();
        }
        return response()->json(['message' => 'Abscences restaurées']);
    }

    public function supprimeSelected(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $abscence = Abscence::find($id);
            if (!$abscence) {
                return response()->json(['message' => 'Abscence non trouvée'], 404);
            }
            $abscence->archiver = 0;
            $abscence->supprimer = 1;
            $abscence->save();
        }
        return response()->json(['message' => 'Abscences supprimées']);
    }

    public function absencePerMonth()
    {
        $monthsData = DB::table('abscences')
        ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
        ->join('formations', 'formations.id', '=', 'abscences.formation_id')
        ->join('cours', 'cours.id', '=', 'abscences.cour_id')
        ->select(DB::raw('MONTH(abscences.created_at) as month'), DB::raw('SUM(abscences.nbreHeureAbs) as count'))
        ->groupBy(DB::raw('MONTH(abscences.created_at)'))

        // $monthsData = Abscence::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            // ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

            $result = [];

            // Fill array with 0 count for all months
            for ($i = 1; $i <= 12; $i++) {

                $result[$i] = ['month' => $i, 'count' => 0];
            }

            // Update counts for months with data
            foreach ($monthsData as $data) {
                $result[$data->month] = ['month' => $data->month, 'count' => $data->count];
            }
        return response()->json(['absencesPerMonth' => array_values($result)]);
    }

    public function absence_filiere_enPourcentage()
    {

        $nbreEtud = DB::table('abscences')
        ->join('formations', 'formations.id', '=', 'abscences.formation_id')
        ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'abscences.etudiant_id')
        ->join('cours', 'cours.id', '=', 'abscences.cour_id')
        // ->select('formations.nomForm', DB::raw('COUNT(*) as count'))
        ->select(DB::raw('SUM(abscences.nbreHeureAbs) as count,formations.nomForm'))
        // ->select(DB::raw('SUM(abscences.nbreHeureAbs) as count,formations.nomForm'), DB::raw('COUNT(abscences.nbreHeureAbs) as totalAbsences'))
        ->groupBy('formations.nomForm')
        ->get();

        $totalAbsences = Abscence::sum('nbreHeureAbs');

        $result = [];

        foreach ($nbreEtud as $data) {
            $percentage = ($data->count / $totalAbsences) * 100;
            $result[] = ['filiere' => $data->nomForm, 'count' => $data->count, 'percentage' => $percentage, 'totalAbsences' =>$totalAbsences];
        }

        return response()->json(['absenceFiliereParPercentage' => $result]);
    }


}
