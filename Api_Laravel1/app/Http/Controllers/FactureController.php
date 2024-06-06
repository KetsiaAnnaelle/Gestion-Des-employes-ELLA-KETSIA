<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Models\facture;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    /**
         * Display a listing of the resource.
         */
        public function index()
        {

            $factures = DB::table('factures')
                ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                ->join('formations', 'formations.id', '=', 'factures.formation_id')
                ->select(DB::raw('factures.total as scolarite, formations.nomForm'),'factures.created_at','factures.total','factures.paye','factures.restant','factures.echeance',
                'factures.status','inscription_etudiants.nomEtud','inscription_etudiants.prenomEtud','factures.etudiant_id')
                ->where('factures.supprimer',0)
                ->Where('factures.archiver',0)
                ->orderBy('factures.id','desc')
                ->get();

            return response()->json($factures);

        }

           /**
             * Display a listing of the resource.
             */
            public function factArchiver()
            {

                $factures = DB::table('factures')
                                ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                                ->join('formations', 'formations.id', '=', 'factures.formation_id')
                                ->select('factures.id','factures.total','factures.formation_id','factures.etudiant_id','factures.paye','factures.restant','factures.echeance','factures.status','factures.supprimer','factures.archiver','factures.created_at','inscription_etudiants.nomEtud','inscription_etudiants.prenomEtud','formations.nomForm')
                                ->where('factures.supprimer',0)
                                ->Where('factures.archiver',1)
                                ->orderBy('id','desc')
                                ->get();

                            return response()->json($factures);

            }

            /**
                     * Display a listing of the resource.
                     */
                    public function factSupprimer()
                    {

                    $factures = DB::table('factures')
                                    ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                                    ->join('formations', 'formations.id', '=', 'factures.formation_id')
                                    ->select('factures.id','factures.total','factures.formation_id','factures.etudiant_id','factures.paye','factures.restant','factures.echeance','factures.status','factures.supprimer','factures.archiver','factures.created_at','inscription_etudiants.nomEtud','inscription_etudiants.prenomEtud','formations.nomForm')
                                    ->where('factures.supprimer',1)
                                    ->Where('factures.archiver',0)
                                    ->orderBy('id','desc')
                                    ->get();

                                return response()->json($factures);

                    }

             /**
             * Display a listing of the resource.
             */
            public function seeFactures(string $id)
            {

              $facture=facture::find($id)
                      ->get();

                return response()->json($facture);

            }



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
                    facture::create([
                        'total'=> $request->input('total'),
                        'paye'=> $request->input('paye'),
                        'restant'=> $request->input('restant'),
                        'echeance'=> $request->input('echeance'),
                        'status'=> $request->input('status'),
                        'formation_id'=> $request->input('formation_id'),
                        'archiver'=> 0,
                         'supprimer'=> 0,
                        'etudiant_id'=> $request->input('etudiant_id'),
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
         * Display the specified resource.
         */
        public function show(string $id, string $nomForm)
        {

           $facture = DB::table('factures')
                        ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                        ->join('formations', 'formations.id', '=', 'factures.formation_id')
                        ->select('factures.id','factures.total','factures.formation_id','factures.etudiant_id','factures.paye','factures.restant','factures.echeance','factures.status','factures.supprimer','factures.archiver','factures.created_at','inscription_etudiants.nomEtud','inscription_etudiants.prenomEtud','formations.nomForm','formations.scolariteForm')
                        ->where('factures.etudiant_id',$id)
                        ->where('formations.nomForm',$nomForm)
                        ->get();
            return response()->json($facture);
        }

        public function showEtud(string $id)
        {

            $facture = DB::table('factures')
                ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                ->join('formations', 'formations.id', '=', 'factures.formation_id')
                ->select('factures.id','factures.formation_id','factures.created_at','factures.total','factures.paye','factures.restant','factures.echeance',
                'factures.status','inscription_etudiants.nomEtud','inscription_etudiants.prenomEtud','factures.etudiant_id','factures.supprimer','factures.archiver','formations.nomForm','formations.scolariteForm')
                ->where('factures.etudiant_id',$id)
                ->where('factures.supprimer',0)
                ->Where('factures.archiver',0)
                ->get();

            return response()->json($facture);

        }

        public function showFact(string $id)
        {

            $facture = DB::table('factures')
                ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                ->join('formations', 'formations.id', '=', 'factures.formation_id')
                ->select('factures.id','factures.total','factures.formation_id','factures.etudiant_id','factures.paye','factures.restant','factures.echeance','factures.status','factures.supprimer','factures.archiver','factures.created_at','inscription_etudiants.nomEtud','inscription_etudiants.prenomEtud','formations.nomForm','formations.scolariteForm')
                ->where('factures.id',$id)
                ->first();

            return response()->json($facture);
        }

      

        public function showFactEtud()
        {
            try {
                $facture = DB::table('inscription_etudiants')
                    ->leftJoin('factures', function ($join) {
                        $join->on('inscription_etudiants.id', '=', 'factures.etudiant_id');
                    })
                    ->leftJoin('formations', 'formations.id', '=', 'factures.formation_id')
                    ->select('inscription_etudiants.id', 'inscription_etudiants.nomEtud', 'inscription_etudiants.prenomEtud','factures.total','factures.paye','formations.nomForm')
                    ->groupBy('inscription_etudiants.id', 'inscription_etudiants.nomEtud')
                    ->get();

                return response()->json($facture);
            } catch (\Throwable $th) {
                return response()->json($th);
            }
        }

        public function FactureParFiliere()
        {
            try {
                $facture = DB::table('formations')
                    ->leftJoin('factures', function ($join) {
                        $join->on('formations.id', '=', 'factures.formation_id');
                    })
                    ->leftJoin('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                    ->select(DB::raw('formations.id, formations.nomForm, COALESCE(COUNT(factures.id), 0) as nombre'))
                    ->groupBy('formations.id', 'formations.nomForm')
                    ->get();

                return response()->json($facture);
            } catch (\Throwable $th) {
                return response()->json($th);
            }
        }

        public function factureForm(string $nomForm)
        {

            $facture = DB::table('factures')
                ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                ->join('formations', 'formations.id', '=', 'factures.formation_id')
                ->select('factures.id','factures.total','factures.formation_id','factures.etudiant_id','factures.paye','factures.restant','factures.echeance','factures.status','factures.supprimer','factures.archiver','factures.created_at','inscription_etudiants.nomEtud','inscription_etudiants.prenomEtud','formations.nomForm','formations.scolariteForm')
                ->where('formations.nomForm',$nomForm)
                ->get();
            return response()->json($facture);
        }

            /**
             * Permet de rechercher un produit dont le nom est parmi les caracteres envoyés
             */
            
            /**
             * Update the specified resource in storage.
             */

            public function update(Request $request, string $id)
            {
                try {
                    $factures=facture::find($id);
                    $factures->update([
                        'total'=> $request->input('total'),
                        'paye'=> $request->input('paye'),
                        'restant'=> $request->input('restant'),
                        'echeance'=> $request->input('echeance'),
                        'status'=> $request->input('status'),
                    ]);
                    return response()->json([
                        'message'=>$id
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
                $facture=facture::find($id);
                $facture->delete();

                return response()->json([
                    'message'=>true
                ]);
            }

           // Marquer une absence comme supprimée
            public function supprimer($id)
            {
                $facture = facture::find($id);
                if (!$facture) {
                    return response()->json(['message' => 'Facture non trouvée'], 404);
                }

                $facture->supprimer = 1; // Marquer comme supprimée
                $facture->archiver = 0; // Marquer comme supprimée
                $facture->save();

                return response()->json(['message' => 'Facture supprimée']);
            }

            // Archiver une absence
            public function archiver($id)
            {
                $facture = facture::find($id);
                if (!$facture) {
                    return response()->json(['message' => 'Facture non trouvée'], 404);
                }

                $facture->archiver = 1; // Marquer comme archivée
                $facture->supprimer = 0; // Marquer comme supprimée
                $facture->save();

                return response()->json(['message' => 'Facture archivée']);
            }

             // Archiver une absence
                public function archiverRestaurer($id)
                {
                    $facture = Facture::find($id);
                    if (!$facture) {
                        return response()->json(['message' => 'Facture non trouvée'], 404);
                    }

                    $facture->archiver = 0; // Marquer comme non archivée
                    $facture->supprimer = 0; // Marquer comme non supprimée
                    $facture->save();

                    return response()->json(['message' => 'Facture archivée']);
                }

                    public function deleteSelected(Request $request)
                    {
                       $datas = $request->input('data');

                        foreach ($datas as $data) {
                            $fact = Facture::find($data);
                            if (!$fact) {
                                return response()->json(['message' => 'facture non trouvée'], 404);
                            }
                            $fact->delete();
                        }
                        return response()->json(['message' => 'factures supprimer definitivement']);
                    }

                    public function restauresSelected(Request $request)
                    {
                        $ids = $request->input('ids');

                        foreach ($ids as $id) {
                            $fact = Facture::find($id);
                            if (!$fact) {
                                return response()->json(['message' => 'facture non trouvé'], 404);
                            }
                            $fact->archiver = 1;
                            $fact->supprimer = 0;
                            $fact->save();
                        }
                        return response()->json(['message' => 'factures restaurés']);
                    }

                    public function restaureSelected(Request $request)
                    {
                        $ids = $request->input('ids');

                        foreach ($ids as $id) {
                            $fact = Facture::find($id);
                            if (!$fact) {
                                return response()->json(['message' => 'facture non trouvé'], 404);
                            }
                            $fact->archiver = 0;
                            $fact->supprimer = 0;
                            $fact->save();
                        }
                        return response()->json(['message' => 'factures restaurés']);
                    }

                    public function supprimeSelected(Request $request)
                    {
                        $ids = $request->input('ids');

                        foreach ($ids as $id) {
                            $fact = Facture::find($id);
                            if (!$fact) {
                                return response()->json(['message' => 'facture non trouvé'], 404);
                            }
                            $fact->archiver = 0;
                            $fact->supprimer = 1;
                            $fact->save();
                        }
                        return response()->json(['message' => 'factures supprimés']);
                    }

                    public function ScolariteParFiliere()
                    {
                        try {
                            // $nbreEtud = InscriptionEtudiant::where('formation_id', $formation_id)->count();
                            $scolarite = DB::table('factures')
                                        // ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                                        ->join('formations', 'formations.id', '=', 'factures.formation_id')
                                        ->select('factures.id','factures.total','factures.formation_id','formations.id','formations.nomForm')
                                        ->groupBy('formations.nomForm')
                                        ->where('factures.supprimer',0)
                                        ->Where('factures.archiver',0)
                                        ->get();

                            return response()->json($scolarite);
                            // dd($nbreEtud);
                        } catch (\Throwable $th) {
                            return response()->json($th);
                        }

                    }

                    public function scolarite_filiere()
                    {
                
                        $nbreEtud = DB::table('factures')
                        ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'factures.etudiant_id')
                        ->join('formations', 'formations.id', '=', 'factures.formation_id')
                        ->select('formations.nomForm', DB::raw('factures.total as scolarite'),DB::raw('COUNT(inscription_etudiants.formation_id) as nombre,formations.nomForm'))
                        ->groupBy('formations.nomForm')
                        ->get();
                        // $totalStudents = For::count();
                
                        $result = [];
                
                        foreach ($nbreEtud as $data) {
                            $revenu = $data->scolarite / $data->nombre;
                            $result[] = ['filiere' => $data->nomForm, 'count' => $data->nombre, 'revenu' => $revenu];
                        }
                
                        return response()->json(['revenu' => $result]);
                    }




    }
