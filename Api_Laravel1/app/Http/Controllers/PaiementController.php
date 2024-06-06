<?php

namespace App\Http\Controllers;

use App\Models\InscriptionEtudiant;
use App\Models\Facture;
use App\Models\Formation;
use App\Models\Paiement;
use App\Models\PaiementFacture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $paiements = Paiement::all();

        $paiements = DB::table('paiements')
        ->join('formations', 'formations.id', '=', 'paiements.formation_id')
        ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'paiements.Etudiant_id')
        ->select('paiements.id',
            'paiements.RefPaiement',
            'paiements.MontantPaiement',
            'paiements.MoyenPaiement',
            'paiements.MotifPaiement',
            'paiements.ProchainPaiement',
            'paiements.Etudiant_id',
            'paiements.formation_id',
            'inscription_etudiants.nomEtud',
            'formations.nomForm',
            'paiements.created_at',

        )
        ->where('paiements.archived_at',null)
        ->where('paiements.deleted_at',null)
        ->orderBy('paiements.created_at','desc')
        ->get();
        // ->paginate(10);

        return response()->json($paiements);
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
    public function store(Request $request)
    {

       try {
            Paiement::create([

                'RefPaiement'=>$request->input('RefPaiement'),
                'MontantPaiement'=> $request->input('MontantPaiement'),
                'MoyenPaiement'=> $request->input('MoyenPaiement'),
                'MotifPaiement'=> $request->input('MotifPaiement'),
                'ProchainPaiement'=> $request->input('ProchainPaiement'),
                'Etudiant_id'=> $request->input('Etudiant_id'),
                'formation_id'=> $request->input('formation_id'),
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

    public function GetEtudiantsAndFiliere(){
        $etudiants = DB::table('inscription_etudiants')
        ->select(
            'inscription_etudiants.id',
            'inscription_etudiants.nomEtud', 'inscription_etudiants.prenomEtud',
        )
        ->get();

        $Fileres = DB::table('formations')
        ->select(
            'formations.id',
            'formations.nomForm'
        )
        ->get();

        $response = [
            'etudiants' =>$etudiants,
            'Fileres' =>$Fileres,
        ];

        return response()->json($response);
    }

    public function DeletePaiement(string $id){

        // $etudiant = InscriptionEtudiant::find($id);
        // $etudiant = EtudiantResource::collection(InscriptionEtudiant::withTrashed()->find($id));
        $Paiement = Paiement::withTrashed()->find($id);

        if (!$Paiement) {
            return response()->json([
                'message'=>'Etudiant non trouvé'
            ]);
        }

        if (!$Paiement ->trashed()) {
            return response()->json([
                'message'=>"Cet Etudiant n'est pas dans la corbeille"
            ]);
        }
        $Paiement->forceDelete();

        return response()->json([
            'message'=>'Etudiant supprime definitivement avec succes',
        ]);
    }


    //Fonction pour un etudiant en corbeille: recuperer les etudiants marques come supprime et les afficher dans la page de la corbeiile

    public function AfficherLesPaiementsDansLaCorbeille (){

        // $PaiementDansLaCorbeille = Paiement::onlyTrashed()->get();

        // $etudiantDansLaCorbeille = EtudiantResource::collection(InscriptionEtudiant::onlyTrashed()->get());
        $PaiementDansLaCorbeille = DB::table('paiements')
            ->join('formations','formations.id', '=', 'paiements.formation_id')
            ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'paiements.Etudiant_id')
            ->select('paiements.id',
            'paiements.RefPaiement',
            'paiements.MontantPaiement',
            'paiements.MoyenPaiement',
            'paiements.MotifPaiement',
            'paiements.ProchainPaiement',
            'paiements.Etudiant_id',
            'paiements.formation_id',
            'inscription_etudiants.nomEtud',
            'formations.nomForm',
            'paiements.created_at',
            'paiements.deleted_at',
            'paiements.archived_at',
            'formations.nomForm')
        ->where('paiements.deleted_at', '!=',null)
            // ->onlyTrashed()
        ->get();

        return response()->json($PaiementDansLaCorbeille);
    }

    //fonction pour mettre un etudiant dans la corbeille
    public function MettreDansLaCorbeille(string $id){
        $Paiement=Paiement::find($id);
        //utiliser la suppression douce (soft delete) pour mettre un etudiant dans la corbeille

        Paiement::whereIn('id',$Paiement)->delete();
        // Paiement::where('id',$Paiement)->delete();

        return response()->json(['Paiement Mis En Corbeille Avec Succes']);

    }

    public function RestaurerPaiementDansLaCorbeille(string $id){

        $paiement = Paiement::withTrashed()->find($id);
        // $paiement = Paiement::where('deleted_at','!=', null)->find($id);

        // $paiement = Paiement::where('deleted_at','!=',null)->find($id);

        // $paiement = DB::table('paiements')
        //     ->join('formations','formations.id', '=', 'paiements.formation_id')
        //     ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'paiements.Etudiant_id')
        //     ->select('paiements.id',
        //         'paiements.RefPaiement',
        //         'paiements.MontantPaiement',
        //         'paiements.MoyenPaiement',
        //         'paiements.MotifPaiement',
        //         'paiements.ProchainPaiement',
        //         'paiements.Etudiant_id',
        //         'paiements.formation_id',
        //         'inscription_etudiants.nomEtud',
        //         'formations.nomForm',
        //         'paiements.created_at',
        //         'paiements.deleted_at',
        //         'paiements.archived_at',
        //         'formations.nomForm')
        //         ->where('paiements.deleted_at','!=', null)
        //         ->find($id);
            // ->onlyTrashed()


        $paiement->restore(); // cela difinira la date de suppression dans la colonne delete_at

        return response()->json([
            'message'=>'paiement restauré avec succès',
        ]);
    }

    //pour gerer l'achivage d'un etudiant
    public function archivePaiement(string $id)

    {
        // DB::table('paiements')
        // ->join('formations', 'formations.id', '=', ' paiements.formation_id')
        // ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'paiements.Etudiant_id')
        // ->select(
        //     'paiements.id',
        //     'paiements.RefPaiement',
        //     'paiements.MontantPaiement',
        //     'paiements.MoyenPaiement',
        //     'paiements.MotifPaiement',
        //     'paiements.ProchainPaiement',
        //     'paiements.Etudiant_id',
        //     'paiements.formation_id',
        //     'inscription_etudiants.nomEtud',
        //     'formations.nomForm',
        //     'paiements.created_at',
        //     'paiements.deleted_at',
        //     'paiements.archived_at',
        //     )
        // // ->where('archived_at',null)
        // // ->orderBy('created_at','desc')
        // ->find($id)

        $paiement = Paiement::find($id);
        $paiement ->update([
            'archived_at'=>now()
        ]);

        return response()->json(['message'=>now()]);

    }

    public function AfficherLesPaiementsArchivés (){
        $paiement = DB::table('paiements')
            ->join('formations', 'formations.id', '=', 'paiements.formation_id')
            ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'paiements.Etudiant_id')
            ->select('paiements.id',
            'paiements.RefPaiement',
            'paiements.MontantPaiement',
            'paiements.MoyenPaiement',
            'paiements.MotifPaiement',
            'paiements.ProchainPaiement',
            'paiements.Etudiant_id',
            'paiements.formation_id',
            'inscription_etudiants.nomEtud',
            'formations.nomForm',
            'paiements.created_at',
            'paiements.deleted_at',
            'paiements.archived_at',
            'formations.nomForm')


        ->whereNotNull('paiements.archived_at')
        ->orderBy('paiements.created_at','desc')
        ->get();

        // $paiement = Paiement::whereNotNull('archived_at')->get();

        return response()->json($paiement);
    }

    public function RestaurerPaiementArchivé(string $id){

        $paiement = Paiement::find($id);


        if (!$paiement) {
            return response()->json([
                'message'=>'Etudiant non trouvé'
            ]);
        }

        // $etudiant->formation->nomForm;
        //Reinitialiser la colonne archived_at pour restaurer l'etudiant
        $paiement->update([
            'archived_at'=>null
        ]);

        return response()->json([
            'message'=>'Etudiant restauré avec succès',
            $paiement
        ]);
    }

    //supprimer un paiement quand on a selectionne
    public function deleteSelected(Request $request)
    {

        $datas = $request->input('data');

        foreach ($datas as $data) {
            $paiement = PaiementFacture::find($data);
            if (!$paiement) {
                return response()->json(['message' => 'paiement non trouvé'], 404);
            }
            $paiement->delete();
        }
        return response()->json(['message' => 'paiements supprimer definitivement']);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paiements = DB::table('paiements')
        ->join('formations', 'formations.id', '=', 'paiements.formation_id')
        ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'paiements.Etudiant_id')
        ->select('paiements.id',
            'paiements.RefPaiement',
            'paiements.MontantPaiement',
            'paiements.MoyenPaiement',
            'paiements.MotifPaiement',
            'paiements.ProchainPaiement',
            'paiements.Etudiant_id',
            'paiements.formation_id',
            'inscription_etudiants.nomEtud',
            'inscription_etudiants.prenomEtud',
            'formations.nomForm',
            'paiements.created_at',

        )
        ->where('paiements.archived_at',null)
        ->where('paiements.deleted_at',null)
        ->where('paiements.etudiant_id',$id)
        ->get();

        return response()->json($paiements);
    }

    public function showProchainPaiementParFormation(string $nomForm)
    {
        try {
            $filiere = Formation::where('nomForm', $nomForm)->first();

            // Récupérer tous les étudiants
            $etudiants = InscriptionEtudiant::where('formation_id', $filiere->id)
                                            ->whereNull('archived_at')
                                            ->whereNull('deleted_at')
                                            ->get();

            // Tableau pour stocker la date du prochain paiement de chaque étudiant
            $prochainsPaiementsParEtudiant = [];

            foreach ($etudiants as $etudiant) {
                if (!$etudiant->archived_at || !$etudiant->deleted_at) {
                    // Récupérer la date du prochain paiement de l'étudiant (s'il en a un)
                    $prochainPaiement = Paiement::where('Etudiant_id', $etudiant->id)
                        ->whereNull('archived_at')
                        ->whereNull('deleted_at')
                        ->max('ProchainPaiement');

                    // Si l'étudiant n'a pas encore fait de paiement, obtenir la date d'échéance de sa facture
                    if (!$prochainPaiement) {
                        $prochainPaiement = Facture::where('etudiant_id', $etudiant->id)
                            ->where('archiver', 0)
                            ->where('supprimer', 0)
                            ->max('echeance');
                    }

                    // Stocker la date du prochain paiement de l'étudiant dans le tableau
                    $prochainsPaiementsParEtudiant[$etudiant->id] = $prochainPaiement;

                }
            }

            $result = array_values($prochainsPaiementsParEtudiant);

            return response()->json($result);

            // Maintenant, $prochainsPaiementsParEtudiant contient la date du prochain paiement de chaque étudiant
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
    public function showCreated_atParFormation(string $nomForm)
    {
        try {
            $filiere = Formation::where('nomForm', $nomForm)->first();

            // Récupérer tous les étudiants
            $etudiants = InscriptionEtudiant::where('formation_id', $filiere->id)
                                            ->whereNull('archived_at')
                                            ->whereNull('deleted_at')
                                            ->get();

            // Tableau pour stocker la date du prochain paiement de chaque étudiant
            $Created_atParEtudiant = [];

            foreach ($etudiants as $etudiant) {
                if (!$etudiant->archived_at || !$etudiant->deleted_at) {
                    // Récupérer la date du prochain paiement de l'étudiant (s'il en a un)
                    $Created_at = Paiement::where('Etudiant_id', $etudiant->id)
                        ->whereNull('archived_at')
                        ->whereNull('deleted_at')
                        ->max('created_at');

                    // Si l'étudiant n'a pas encore fait de paiement, obtenir la date d'échéance de sa facture
                    if (!$Created_at) {
                        $Created_at = Facture::where('etudiant_id', $etudiant->id)
                            ->where('archiver', 0)
                            ->where('supprimer', 0)
                            ->max('created_at');
                    }

                    // Stocker la date du prochain paiement de l'étudiant dans le tableau
                    $Created_atParEtudiant[$etudiant->id] = $Created_at;

                }
            }

            $result = array_values($Created_atParEtudiant);

            return response()->json($result);

            // Maintenant, $prochainsPaiementsParEtudiant contient la date du prochain paiement de chaque étudiant
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }


    public function showPaieEtud()
    {
        try {
            $facture = DB::table('inscription_etudiants')
                ->leftJoin('paiements', function ($join) {
                    $join->on('inscription_etudiants.id', '=', 'paiements.etudiant_id');
                })
                ->leftJoin('formations', 'formations.id', '=', 'paiements.formation_id')
                ->select('inscription_etudiants.id', 'inscription_etudiants.nomEtud', 'inscription_etudiants.prenomEtud','paiements.MontantPaiement','paiements.ProchainPaiement')
                ->groupBy('inscription_etudiants.id', 'inscription_etudiants.nomEtud')
                ->get();

            return response()->json($facture);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function showPaieEtudMontant(string $nomForm)
    {
        try {

            $filiere = Formation::where('nomForm', $nomForm)
                                ->first();


            // Récupérer tous les étudiants
            $etudiants = InscriptionEtudiant::where('formation_id',$filiere->id)
                                            ->whereNull('archived_at')
                                            ->whereNull('deleted_at')
                                            ->get();

            // Tableau pour stocker le montant total payé par chaque étudiant
            $montantsTotalPayesParEtudiant = [];

            foreach ($etudiants as $etudiant) {

                if (!$etudiant->archived_at || !$etudiant->deleted_at) {

                    // Calculer le montant total payé par le biais des factures
                    $montantTotalFactures = Facture::where('etudiant_id', $etudiant->id)
                        ->where('archiver', 0)
                        ->where('supprimer', 0)
                        ->sum('paye');

                    // Calculer le montant total payé par le biais des paiements
                    $montantTotalPaiements = Paiement::where('Etudiant_id', $etudiant->id)
                        ->whereNull('archived_at')
                        ->whereNull('deleted_at')
                        ->sum('MontantPaiement');

                    // Montant total payé par l'étudiant (somme des montants de factures et de paiements)
                    $montantTotalPaye = $montantTotalFactures + $montantTotalPaiements;

                    // Stocker le montant total payé par l'étudiant dans le tableau
                    $montantsTotalPayesParEtudiant[$etudiant->id] = $montantTotalPaye;
                }
            }

            return response()->json([$montantsTotalPayesParEtudiant]);


            // Maintenant, $montantsTotalPayesParEtudiant contient le montant total payé par chaque étudiant (clé : ID de l'étudiant, valeur : montant total payé)
        } catch (\Throwable $th) {
            return $th;
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function ShowInfosPaiementEdit(string $id)
    {

      $paiement=Paiement::find($id)
              ->get();

        return response()->json($paiement);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $paiement=Paiement::find($id);
            $paiement->update([
                'RefPaiement'=>$request->input('RefPaiement'),
                'formation_id'=> $request->input('formation_id'),
                'Etudiant_id'=> $request->input('Etudiant_id'),
                'MontantPaiement'=> $request->input('MontantPaiement'),
                'MoyenPaiement'=> $request->input('MoyenPaiement'),
                'MotifPaiement'=> $request->input('MotifPaiement'),
                'ProchainPaiement'=> $request->input('ProchainPaiement'),
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
        //
    }

    public function showPaieMontant(string $id)
    {
        try {


            // Récupérer tous les étudiants
            $etudiants = InscriptionEtudiant::where('id',$id)
                                            ->whereNull('archived_at')
                                            ->whereNull('deleted_at')
                                            ->get();

            // Tableau pour stocker le montant total payé par chaque étudiant
            $montantsTotalPayesParEtudiant = [];

            foreach ($etudiants as $etudiant) {

                if (!$etudiant->archived_at || !$etudiant->deleted_at) {

                    // Calculer le montant total payé par le biais des factures
                    $montantTotalFactures = Facture::where('etudiant_id', $etudiant->id)
                        ->where('archiver', 0)
                        ->where('supprimer', 0)
                        ->sum('paye');

                    // Calculer le montant total payé par le biais des paiements
                    $montantTotalPaiements = Paiement::where('Etudiant_id', $etudiant->id)
                        ->whereNull('archived_at')
                        ->whereNull('deleted_at')
                        ->sum('MontantPaiement');

                    // Montant total payé par l'étudiant (somme des montants de factures et de paiements)
                    $montantTotalPaye = $montantTotalFactures + $montantTotalPaiements;

                    // Stocker le montant total payé par l'étudiant dans le tableau
                    $montantsTotalPayesParEtudiant[$etudiant->id] = $montantTotalPaye;
                }
            }

            return response()->json([$montantsTotalPayesParEtudiant]);


            // Maintenant, $montantsTotalPayesParEtudiant contient le montant total payé par chaque étudiant (clé : ID de l'étudiant, valeur : montant total payé)
        } catch (\Throwable $th) {
            return $th;
        }
    }
}