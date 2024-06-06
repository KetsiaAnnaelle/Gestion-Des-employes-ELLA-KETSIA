<?php

namespace App\Http\Controllers;

use App\Http\Resources\EtudiantResource;
use App\Models\Abscence;
use App\Models\Formation;
use App\Models\InscriptionEtudiant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class EtudiantController extends Controller
{
    public function index(){
        return response()->json();
    }
    // public function dashboard(){
    //     return view('pages.etudiants.dashboard');
    // }

    //afficher tous les etudiants inscrits
    public function inscription(){
       $etudiants = DB::table('inscription_etudiants')
            ->join('formations', 'formations.id', '=', 'inscription_etudiants.formation_id')
            ->select('inscription_etudiants.id',
                'inscription_etudiants.typeEtud',
                'inscription_etudiants.nomEtud',
                'inscription_etudiants.prenomEtud',
                'inscription_etudiants.birthday',
                'inscription_etudiants.sexe',
                'inscription_etudiants.cni',
                'inscription_etudiants.niveau',
                'inscription_etudiants.ville',
                'inscription_etudiants.pays',
                'inscription_etudiants.telEtud',
                'inscription_etudiants.whatsappEtud',
                'inscription_etudiants.emailEtud',
                'inscription_etudiants.nomTuteur',
                'inscription_etudiants.telTuteur',
                'inscription_etudiants.formation_id',
                'inscription_etudiants.section',
                'inscription_etudiants.motivation',
                'inscription_etudiants.decouverte',
                'inscription_etudiants.profil',
                'inscription_etudiants.diplome',
                'inscription_etudiants.photocopieCni',
                'inscription_etudiants.created_at',
                'inscription_etudiants.deleted_at',
                'inscription_etudiants.archived_at',
                'formations.nomForm'
            )
            ->where('inscription_etudiants.archived_at',null)
            ->where('inscription_etudiants.deleted_at',null)
            ->orderBy('created_at','desc')
            ->get();
        // return EtudiantResource::collection(InscriptionEtudiant::orderBy('created_at','desc')->where('archived_at',null)->get());
        // return InscriptionEtudiant::orderBy('created_at','desc')->where('archived_at',null)->get();

        return response()->json($etudiants);
    }

    //pour inscrire un etudiant
    public function inscrireEtudiant(Request $request){

        $validator = Validator::make($request->all(), [

            'typeEtud'=>'required',
            'nomEtud'=> 'required',
            'prenomEtud'=> 'required',
            'birthday'=> 'required',
            'sexe'=> 'required',
            'cni'=> 'required',
            'niveau'=> 'required',
            'ville'=> 'required',
            'pays'=> 'required',
            'telEtud'=> 'required',
            'whatsappEtud'=> 'required',
            'emailEtud'=> 'required|email|unique:inscription_etudiants',
            'nomTuteur'=> 'required',
            'telTuteur'=> 'required',
            'formation_id'=> 'required',
            'section'=> 'required',
            'motivation'=> 'required|max:255',
            'decouverte'=> 'required',
            'profil' => 'required|image|mimes:png,jpeg,gif,svg,webp|max:4000',
            'diplome' => 'required|image|mimes:png,jpeg,gif,svg,webp|max:4000',
            'photocopieCni' => 'required|image|mimes:png,jpeg,gif,svg,webp|max:4000',
        ]);

        if ($validator->fails()) {
            // return response()->json(['errors'=>$validator->getMessageBag()]);
            return response()->json(['errors'=>$validator->errors()],422);
                        // ->withErrors($validator)
                        // ->withInput();
        }
        else {
            $file1 = $request->file('profil');
            $file2 = $request->file('diplome');
            $file3 = $request->file('photocopieCni');

            $name1 = time().$file1->getClientOriginalName();
            $name2 = time().$file2->getClientOriginalName();
            $name3 = time().$file3->getClientOriginalName();
            // dd($request->all());

            InscriptionEtudiant::create([

                'typeEtud'=> $request->input('typeEtud'),
                'nomEtud'=> $request->input('nomEtud'),
                'prenomEtud'=> $request->input('prenomEtud'),
                'birthday'=> $request->input('birthday'),
                'sexe'=> $request->input('sexe'),
                'cni'=> $request->input('cni'),
                'niveau'=> $request->input('niveau'),
                'ville'=> $request->input('ville'),
                'pays'=> $request->input('pays'),
                'telEtud'=> $request->input('telEtud'),
                'whatsappEtud'=> $request->input('whatsappEtud'),
                'emailEtud'=> $request->input('emailEtud'),
                'nomTuteur'=> $request->input('nomTuteur'),
                'telTuteur'=> $request->input('telTuteur'),
                'formation_id'=> $request->input('formation_id'),
                'section'=> $request->input('section'),
                'motivation'=> $request->input('motivation'),
                'decouverte'=> $request->input('decouverte'),
                'profil'=> $name1,
                'diplome'=> $name2,
                'photocopieCni'=> $name3,
            ]);
            // $destination = 'uploadImage';
            $file1->move('uploadImage', $name1); //move regarde directement dans public
            $file2->move('uploadImage', $name2); //move regarde directement dans public
            $file3->move('uploadImage', $name3); //move regarde directement dans public
            // return redirect('/');

            return response()->json([
                'message'=>true,
            ]);
        }

    }

    public function etudiantsParFiliere()
    {
        try {
            // $nbreEtud = InscriptionEtudiant::where('formation_id', $formation_id)->count();
            $nbreEtud = DB::table('inscription_etudiants')
                    ->join('formations', 'formations.id', '=', 'inscription_etudiants.formation_id')
                    ->select(DB::raw('COUNT(inscription_etudiants.formation_id) as nombre,formations.nomForm'))
                    ->groupBy('formations.nomForm')
                    ->get();

            return response()->json($nbreEtud);
            // dd($nbreEtud);
        } catch (\Throwable $th) {
            return response()->json($th);
        }

    }

    public function NbreFiliere(){
        try {
            $nbre = Formation::count()->get();
            
            return response()->json($nbre);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function studentsPerMonth()
    {
        $monthsData = InscriptionEtudiant::select(DB::raw('COUNT(*) as count'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                    ->groupBy(DB::raw('MONTH(created_at)'))
                    ->where('inscription_etudiants.archived_at',null)
                    ->where('inscription_etudiants.deleted_at',null)
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
        return response()->json(['studentsPerMonth' => array_values($result)]);
    }

    public function effectif_filiere_enPourcentage()
    {

        $nbreEtud = DB::table('inscription_etudiants')
        ->join('formations', 'formations.id', '=', 'inscription_etudiants.formation_id')
        ->select('formations.nomForm', DB::raw('COUNT(*) as count'))
        ->groupBy('formations.nomForm')
        ->get();
        $totalStudents = InscriptionEtudiant::count();

        $result = [];

        foreach ($nbreEtud as $data) {
            $percentage = ($data->count / $totalStudents) * 100;
            $result[] = ['filiere' => $data->nomForm, 'count' => $data->count, 'percentage' => $percentage];
        }

        return response()->json(['effectifFiliereParPercentage' => $result]);
    }


    // public function TopEtudiants()
    // {

    //     $topStudents = DB::table('inscription_etudiants')
    //     ->join('conduites','conduites.etudiant_id','=','inscription_etudiants.id' )
    //     ->join('formations', 'formations.id', '=', 'conduites.formation_id')
    //     ->select(DB::raw('conduites.notecond as note,formations.nomForm'),'inscription_etudiants.nomEtud')
    //     ->orderBy('conduites.notecond', 'desc')
    //     // ->groupBy('formations.nomForm')
    //     ->take(5)
    //     ->get();

    //     return response()->json([
    //         // 'meilleurs_etudiants'=>$meilleursEtudiants,
    //         'faibles_etudiants'=>$topStudents,
    //     ]);
    // }


    // supprimer un etudiant definitivement quand il est dans la corbeille
    public function DeleteEtudiant(string $id){

        // $etudiant = InscriptionEtudiant::find($id);
        // $etudiant = EtudiantResource::collection(InscriptionEtudiant::withTrashed()->find($id));
        $etudiant = InscriptionEtudiant::withTrashed()->find($id);

        if (!$etudiant) {
            return response()->json([
                'message'=>'Etudiant non trouvé'
            ]);
        }

        if (!$etudiant ->trashed()) {
            return response()->json([
                'message'=>"Cet Etudiant n'est pas dans la corbeille"
            ]);
        }
        $etudiant->forceDelete();

        return response()->json([
            'message'=>'Etudiant supprime definitivement avec succes',
        ]);
    }

    //Fonction pour un etudiant en corbeille: recuperer les etudiants marques come supprime et les afficher dans la page de la corbeiile

    public function AfficherLesEtudiantsDansLaCorbeille (){

        // $etudiantDansLaCorbeille = EtudiantResource::collection(InscriptionEtudiant::onlyTrashed()->get());
        // $etudiantDansLaCorbeille = InscriptionEtudiant::onlyTrashed()->get();
        $etudiantDansLaCorbeille = InscriptionEtudiant::join('formations','formations.id', '=', 'inscription_etudiants.formation_id')
        ->onlyTrashed()
        ->select('inscription_etudiants.id',
            'formations.nomForm',
            'formations.id',
            'inscription_etudiants.formation_id',
            'inscription_etudiants.nomEtud',
            'inscription_etudiants.prenomEtud',
            'inscription_etudiants.sexe',
            'inscription_etudiants.whatsappEtud',
            'inscription_etudiants.deleted_at',
        )
        ->get();

        return response()->json($etudiantDansLaCorbeille);
    }

    //fonction pour mettre un etudiant dans la corbeille
    public function MettreDansLaCorbeille(string $id){
        // $etudiant = $request->input('etudiant');
        $etudiant=InscriptionEtudiant::find($id);
        //utiliser la suppression douce (soft delete) pour mettre un etudiant dans la corbeille

        InscriptionEtudiant::whereIn('id',$etudiant)->delete();

        return response()->json(['message'=> 'Etudiant mis en corbeille avec succès']);

    }

    public function RestaurerEtudiantDansLaCorbeille(string $id){

        $etudiant = InscriptionEtudiant::withTrashed()->find($id);

        if (!$etudiant) {
            return response()->json([
                'message'=>'Etudiant non trouvé'
            ]);
        }

        if (!$etudiant ->trashed()) {
            return response()->json([
                'message'=>"Cet Etudiant non trouvé"
            ]);
        }

        $etudiant->restore(); // cela difinira la date de suppression dans la colonne delete_at

        return response()->json([
            'message'=>'Etudiant restauré avec succès',
        ]);
    }

    //pour gerer l'achivage d'un etudiant
    public function archive(string $id)
    {
        // $etudiants = DB::table('inscription_etudiants')
        // ->join('formations', 'inscription_etudiants.formation_id', '=', 'formations.id')
        // ->select(
        //     'formation_id',
        //     'inscription_etudiants.id',
        //     'inscription_etudiants.typeEtud',
        //     'inscription_etudiants.nomEtud',
        //     'inscription_etudiants.prenomEtud',
        //     'inscription_etudiants.sexe',
        //     'inscription_etudiants.telEtud',
        //     'inscription_etudiants.whatsappEtud',
        //     'inscription_etudiants.formation_id',
        //     'inscription_etudiants.created_at',
        //     'inscription_etudiants.deleted_at',
        //     'inscription_etudiants.archived_at',
        //     )
        // // ->where('archived_at',null)
        // // ->orderBy('created_at','desc')
        // ->find($id)
        // ->update([
        //     'inscription_etudiants.archived_at'=>now()
        // ]);


        $etudiant = InscriptionEtudiant::find($id);
        $etudiant ->update([
            'archived_at'=>now()
        ]);

        return response()->json(['message'=>now()]);

        // return response()->json(['message'=>now()]);


        // Récupérez la liste des étudiants mise à jour (excluant les étudiants archivés)
        // $etudiants = InscriptionEtudiant::whereNull('archived_at')->get();

        // return response()->json($etudiants);

    }

    public function AfficherLesEtudiantsArchivés (){
        // $etudiants = EtudiantResource::collection(InscriptionEtudiant::whereNotNull('archived_at')->get());
        // $etudiants = InscriptionEtudiant::whereNotNull('archived_at')->get();

        $etudiants = DB::table('inscription_etudiants')
        ->join('formations', 'formations.id', '=', 'inscription_etudiants.formation_id')
        // ->join('inscription_etudiants', 'inscription_etudiants.id', '=', 'paiements.Etudiant_id')
        ->select('inscription_etudiants.id',
        'inscription_etudiants.typeEtud',
        'inscription_etudiants.nomEtud',
        'inscription_etudiants.prenomEtud',
        'inscription_etudiants.birthday',
        'inscription_etudiants.sexe',
        'inscription_etudiants.cni',
        'inscription_etudiants.niveau',
        'inscription_etudiants.ville',
        'inscription_etudiants.pays',
        'inscription_etudiants.telEtud',
        'inscription_etudiants.whatsappEtud',
        'inscription_etudiants.emailEtud',
        'inscription_etudiants.nomTuteur',
        'inscription_etudiants.telTuteur',
        'inscription_etudiants.formation_id',
        'inscription_etudiants.motivation',
        'inscription_etudiants.decouverte',
        'inscription_etudiants.profil',
        'inscription_etudiants.diplome',
        'inscription_etudiants.photocopieCni',
        'inscription_etudiants.created_at',
        'inscription_etudiants.deleted_at',
        'inscription_etudiants.archived_at',
        'formations.nomForm')


        ->whereNotNull('archived_at')
        ->orderBy('created_at','desc')
        ->get();

        return response()->json($etudiants);
    }

    public function DetailsEtudiant(string $id){
        $etudiant = InscriptionEtudiant::find($id);
        $etudiant->formation->nomForm;
        $etudiant->formation->cours;
        // $etudiant->formation->note;
        $etudiant->paiement;
        $etudiant->absence;
        $etudiant->conduite;
        $etudiant->note;
        // $etudiant->absence->cour;
        // $etudiant->cours;
        // $etudiant->note->cours;
        return response()->json($etudiant);
    }

    //Restaurer un etudiant archive
    public function RestaurerEtudiantArchivé(string $id){

        $etudiant = InscriptionEtudiant::find($id);


        if (!$etudiant) {
            return response()->json([
                'message'=>'Etudiant non trouvé'
            ]);
        }

        // $etudiant->formation->nomForm;
        //Reinitialiser la colonne archived_at pour restaurer l'etudiant
        $etudiant->update([
            'archived_at'=>null
        ]);

        return response()->json([
            'message'=>'Etudiant restauré avec succès',
            $etudiant
        ]);
    }


    //afficher les informations de l'etudiant dont on veut modifier les informations
    public function ShowInfosStudentEdit(string $id)
    {
        $etudiants=InscriptionEtudiant::find($id);
        $etudiants->formation->nomForm;
        return response()->json($etudiants);

    }

    //fonction pour editer les informations affichees
    public function EditerStudent(Request $request, string $id)
    {
        try {
            $etudiants=InscriptionEtudiant::find($id);


            $etudiants->update([
                'nomEtud'=> $request->input('nomEtud'),
                'prenomEtud'=> $request->input('prenomEtud'),
                'sexe'=> $request->input('sexe'),
                'niveau'=> $request->input('niveau'),
                'ville'=> $request->input('ville'),
                'pays'=> $request->input('pays'),
                'telEtud'=> $request->input('telEtud'),
                'whatsappEtud'=> $request->input('whatsappEtud'),
                'emailEtud'=> $request->input('emailEtud'),
                'formation_id'=> $request->input('formation_id'),
                'section'=> $request->input('section'),
            ]);

        return response()->json([
            'message'=>true,
        ]);
        } catch (\Throwable $th) {
            return response()->json($th);
        }


    }

    public function selectedFiliere(string $selectedFiliereId)
    {

        $etudiants = DB::table('inscription_etudiants')
        ->join('formations', 'formations.id', '=', 'inscription_etudiants.formation_id')
        ->select('inscription_etudiants.id',
            'inscription_etudiants.nomEtud',
            'inscription_etudiants.prenomEtud',
            'formations.nomForm',

        )
        ->where('inscription_etudiants.formation_id',$selectedFiliereId)
        ->orderBy('inscription_etudiants.nomEtud','asc')
        ->get();

        return response()->json($etudiants);

    }

    //Avoir un etudiant par filiere

    public function GetStudentsByClass(string $filiere){

        // $Students = InscriptionEtudiant::where('formation_id', $filiere)
        $Students = DB::table('inscription_etudiants')
            ->join('formations', 'formations.id', '=', 'inscription_etudiants.formation_id')
            ->select('inscription_etudiants.id', 'formations.id', 'formations.nomForm', 'inscription_etudiants.nomEtud', 'inscription_etudiants.prenomEtud', 'inscription_etudiants.section', 'inscription_etudiants.formation_id','inscription_etudiants.birthday',
            'inscription_etudiants.sexe',
            'inscription_etudiants.niveau',
            'inscription_etudiants.ville',
            'inscription_etudiants.telEtud',
            'inscription_etudiants.nomTuteur',
            'inscription_etudiants.telTuteur',)
            ->where('formations.nomForm', $filiere)
            ->get();
        return response()->json($Students);
    }

    // public function GetStudentsByClass(string $filiere){

    //     $filiere = Formation::findOrFail($filiere);

    //     $Students = $filiere->etudiant;
    //     return response()->json($Students);
    // }


    //avoir toutes les formsations

    public function GetFormations(){

        $formations = DB::table('formations')
            ->select(
                'formations.id',
                'formations.nomForm',
            )
            ->get();
        return response()->json($formations);
    }


    //supprimer un etudiant quand on a selectionne
    public function deleteSelected(Request $request)
    {
        $etudiants = InscriptionEtudiant::find($request->data);
        foreach($etudiants as $etudiant){
            $etudiant->delete();
        }

        return response()->json(true);
    }


    public function paiement(){
        return view('pages.etudiants.paiement');
    }
    public function presence(){
        return view('pages.etudiants.presence');
    }
    public function note(){
        return view('pages.etudiants.note');
    }
    public function stage(){
        return view('pages.etudiants.stage');
    }
    public function conduite(){
        return view('pages.etudiants.conduite');
    }
}
