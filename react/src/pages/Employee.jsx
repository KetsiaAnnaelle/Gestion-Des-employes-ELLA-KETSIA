import React, { useEffect, useState } from 'react';
import Header from '../components/Header';
import { BsArrowDownUp, BsSearch } from 'react-icons/bs';
import { useForm } from 'react-hook-form';
import axios from 'axios';
import { FaEdit, FaTrashAlt } from 'react-icons/fa';
import { useNavigate } from 'react-router';
import Carousel from '../components/Carousel';
import { Link } from 'react-router-dom';


const Employee = () => {
    // Fonction pour ouvrir la modal
    const openModal = () => {
        document.getElementById('myModal').classList.remove('hidden');
    }

    // Fonction pour fermer la modal
    const closeModal = () => {
        document.getElementById('myModal').classList.add('hidden');
    }

    const { register, control, reset, handleSubmit, formState: { errors } } = useForm();


    const [employe, setEmploye] = useState([])


    const onSubmit = async (data) => {
        const formData = new FormData();
        formData.append('nameEmp', data.nameEmp);
        formData.append('surnameEmp', data.surnameEmp);
        formData.append('email', data.email);
        formData.append('birthday', data.birthday);
        formData.append('sexe', data.sexe);
        formData.append('Tel', data.Tel);
        formData.append('poste', data.poste);
        formData.append('salaire', data.salaire);
        
        
        axios.post(`${import.meta.env.VITE_URL}/employe`,formData)
        .then(function (response) {
                console.log(response.data);
                // console.log(data.profil.files[0]);
                JSON.stringify(response.data)
                // setShow(false)
                reset('')
                closeModal()
    
                swal({
                    title: "Ajouté Avec Succès !!!",
                    text: "You clicked the button!",
                    icon: "success",
                    button: "OK",
                    timer: 2000
                });
                getEmploye()
            })
            .catch(function(error)  {
                console.error(error);
            })
    }

    const [params, setparams] = useState([])
    const [idL, setidL] = useState(0)

    const [modalShow, setModalShow] = React.useState(false);
    
    const [NewEmploye, setNewEmploye] = useState([]);
    
    async function getEmploye() {
        try {
            // setload(true)
            const response = await axios.get(`${import.meta.env.VITE_URL}/employe`);
            setEmploye(response.data);
            setNewEmploye(response.data);
            setparams(response.data);
            // setload(false)
            // console.log(response.data)
            //setSelectedRecords(response.data)
        } catch (error) {
            console.error(error);
        }
    }

    const [searchQuery, setSearchQuery] = useState('');
    const keys = ["nameEmp", "surnameEmp", "email", "sexe", "Tel", "poste", "salaire", "birthday"];
    // const [selectedRecords, setSelectedRecords] = useState([]);

    function handleMan(element) {
        setidL(element.id)
        setparams(element)
        setModalShow(true)
    }

    const [delEmpl, setdelEmpl] = useState(false)

    function delEmployee(id) {
        swal({
            title: `Voulez-vous vraiment supprimer cet employe?`,
            icon: "warning",
            buttons: true,
            dangerMode:true,
        })
            .then((willDelete)=>{
                if (willDelete) {
                    // async function delStudents(id) {
                    try {
                        const response = axios.delete(`${import.meta.env.VITE_URL}/force-delete-employe/${id}`);
                        console.log(response);
                        setdelEmpl(!delEmpl);
                        setEmploye(response.data)

                        getEmploye()


                        setTimeout(() => {
                            window.location.reload() //pour actualiser la page automatiquement
                        }, 1000);

                        // getStudentsCorbeille()
                        swal("Employe supprimé definitivement !!!",{
                            icon:"success",
                        });
                    } catch (error) {
                        console.error(error);
                    }

                }
                else{
                    swal("Impossible de supprimer")
                }
            })
    }

    const [selectedRecords, setSelectedRecords] = useState([]);

    const [check, setcheck] = useState([]);

    const handleCheckboxChange = (elementId) => {
        console.log(selectedRecords)
        // Mettez à jour les éléments sélectionnés lorsque les cases à cocher sont cochées/décochées
        if (selectedRecords.includes(elementId)) {
            setSelectedRecords(selectedRecords.filter(id => id.id !== elementId));
            console.log('ok')
        } else {
            setSelectedRecords([...selectedRecords, elementId]);
            console.log('non')
        }
    };


    const checkedAll =(id) =>{
        let searchId = check.find((item)=> item== id)
        let searchAllId = selectedRecords.find((item)=> item== id)
        if (searchId || searchAllId){
            let newArray = check.filter((item)=> item != id)
            let newAArray = selectedRecords.filter((item)=> item != id)
            setcheck(newArray)
            setSelectedRecords(newAArray)
        }else {
            setcheck([...check,id])
            setSelectedRecords([...selectedRecords,id])
        }
    }


    const [isAsc, setisAsc] = useState(false)

    function TrieDateDescendant() {
        let t = employe;
        if (isAsc === false) {
            
            t.sort((a,b) => new Date(a.created_at) - new Date(b.created_at))
            // console.log(t);
        }else{
            t.sort((a,b) => new Date(b.created_at) - new Date(a.created_at))
        }
        setisAsc(v => !v) //ici on affecte l'inverse de la valeur. ca fonctionne seulement avec les booleens
        setallstud(t)
    }

    const [isCrois, setisCrois] = useState(false)

    function TrieNom() {
        let t = employe;
        console.log(t);     
        if (isCrois === false) {
            
            t.sort((a,b) => a.nameEmp.localeCompare(b.nameEmp)) //tri croissant
            // console.log(t);
        }else{
            t.sort((a,b) => b.nameEmp.localeCompare(a.nameEmp))
        }
        setisCrois(v => !v) //ici on affecte l'inverse de la valeur. ca fonctionne seulement avec les booleens
        setallstud(t)
    }



    const handleDeleteSelected = () => {
        if (check.length==0){
            // alert('Cocher au moins un paiement')
            swal({
                title: "Cocher au moins un employé",
                text: "You clicked the button!",
                icon: "error",
                button: "OK", 
                timer: 2000
            })

        }else {

            swal({
                title: `Voulez-vous vraiment supprimer cet/ces employé(s)?`,
                icon: "warning",
                buttons: true,
                dangerMode:true,
            })
                .then((willDelete)=>{
                    if (willDelete) {
                        // async function delStudents(id) {

                        axios.post(`${import.meta.env.VITE_URL}/element-employe`, {
                            data: check,
                        })
                            .then(function (response) {
                                console.log(response.data);

                                setTimeout(() => {
                                    window.location.reload() //pour actualiser la page automatiquement
                                }, 2000);

                                // getStudentsCorbeille()
                                swal("Etudiant(s) supprimé(s) definitivement !!!",{
                                    icon:"success",
                                });
                            })
                            .catch(function (error) {
                                console.log('echec');
                            });

                        // setallstud(response.data)

                    }
                    else{
                        swal("Impossible de supprimer")
                    }
                })


        }
    };

    useEffect(() => {
        getEmploye()
    }, [])


    if (JSON.parse(localStorage.getItem('user')).role !== 'admin') {
        alert("Impossible de consulter cette page car vous n'etes pas administrateur")
        window.location.replace('/')
    }
    

    return (
        <div>
            <Header /> 
            <div className="overflow-hidden whitespace-nowrap" style={{ marginTop: '6.5rem' }}>
                <div className="animate-marquee inline-block py-2 px-4 bg-yellow-100 text-black font-bold">
                    Bienvenue à la page des employés ! Nous sommes ravis de vous avoir parmi nous.
                </div>
            </div>

            <div className="container mx-auto mt-10">
                <div className="flex justify-center">
               
                    <div className="w-full md:w-1/2">
                        <form>
                            <div className="relative mb-3">
                                <input
                                    type="text"
                                    placeholder="Entrer un nom"
                                    className="form-input w-full p-4 rounded-lg border-2 border-gray-300"
                                />
                                <BsSearch className="absolute top-1/2 right-4 transform -translate-y-1/2 text-xl" value={searchQuery} onChange={(e)=>setSearchQuery(console.log(e.target.value))}/>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            {/* <div className="container mx-auto">
                <Carousel/>
            </div> */}
            <div className="flex justify-center my-6000 gap-10 my-10 mx-5">
                <button className="flex items-center justify-center px-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" onClick={openModal}>
                    <svg className="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajouter un employé
                </button>

                <button variant="transparent" className='flex flex-row gap-2 border-0 rounded-md shadow-sm px-2 py-2 hover:bg-red-600 focus:outline-none bg-red-300' onClick={()=>handleDeleteSelected(selectedRecords)}>
                    <FaTrashAlt className="text-red-500"/>
                     Supprimer plusieurs employes
                </button>
            </div>

            <div className="relative overflow-x-auto shadow-md sm:rounded-lg mt-5 mx-10 my-10">
                <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 px-5">
                    <thead className="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3"></th>
                            <th scope="col" class="px-6 py-3">Date d'arrivée <BsArrowDownUp className='fw-bold ms-1'onClick={TrieDateDescendant} style={{ cursor:'pointer' }}/></th>
                            <th scope="col" class="px-6 py-3">Nom<BsArrowDownUp className='fw-bold ms-1' onClick={TrieNom} style={{ cursor:'pointer' }}/></th>
                            <th scope="col" class="px-6 py-3">Prenom</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Sexe</th>
                            <th scope="col" class="px-6 py-3">Tel</th>
                            <th scope="col" class="px-6 py-3">Poste</th>
                            <th scope="col" class="px-6 py-3">Salaire</th>
                            <th scope="col" class="px-6 py-3">Âge</th>
                            <th scope="col" class="px-6 py-3">Actions</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {/* Contenu du tableau */}

                        {
                            NewEmploye.filter(element =>
                                keys.some((key) => {
                                    const value = element[key];
                                    if (typeof value === 'string') {
                                        return value.toLowerCase().includes(searchQuery);
                                    }
                                    return false;
                                })
                            
                            ).map((element,index) => {
                                const isChecked = selectedRecords.includes(element.id);

                                return(
                                    <>
                                        <tr key={index} className="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td className='px-6 py-4'>
                                                <input
                                                    inline
                                                    type="checkbox"
                                                    className="mx-3"
                                                    checked={isChecked}
                                                    onClick={() => checkedAll(element.id)}
                                                />
                                            </td>
                                          
                                            <td className='px-6 py-4'>{element.created_at.split('T')[0]}</td>
                                            <td className='px-6 py-4'>{element.nameEmp}</td>
                                            <td className='px-6 py-4'>{element.surnameEmp}</td>
                                            <td className='px-6 py-4'>{element.email}</td>
                                            <td className='px-6 py-4'>{element.sexe}</td>
                                            <td className='px-6 py-4'>{element.Tel}</td>
                                            <td className='px-6 py-4'>{element.poste}</td>
                                            <td className='px-6 py-4'>{element.salaire}</td>
                                            <td className='px-6 py-4'>{element.birthday}</td>
                                            <td className='px-6 py-4'>
                                              <Link to={`/update/${element.id}`}><FaEdit className='text-blue-500 mx-5' onClick={()=>handleMan(element)} style={{ cursor:'pointer' }}/></Link> 
                                            </td>

                                            <td className='px-6 py-4'>
                                                <FaTrashAlt className="text-red-500" style={{ cursor:'pointer' }} onClick={()=>delEmployee(element.id)}/>
                                            </td>
                                        </tr>

                                    </>

                                )
                            })
                        }
                       
                        {/* Autres lignes du tableau */}
                    <p className='ml-2 my-2 font-bold'>Total des employés: <span className='text-red-500'>{NewEmploye.length}</span> </p>
                    </tbody>
                </table>
            </div>

            {/* Modal */}
            <div id="myModal" className="modal fixed w-full h-full top-0 left-0 flex items-center justify-center hidden" style={{ marginTop: '3.5rem' }}>
                <div className="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

                <div className="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
                    <div className="modal-content py-4 text-left px-6">
                        {/* Contenu de la modal */}
                        <div className="flex justify-between items-center pb-3">
                            <p className="text-2xl font-bold">Ajouter un employe</p>
                            <button className="modal-close cursor-pointer z-50" onClick={closeModal}>
                                <svg className="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                    <path d="M18 1.484L16.516 0 9 7.516 1.484 0 0 1.484 7.516 9 0 16.516 1.484 18 9 10.484 16.516 18 18 16.516 10.484 9 18 1.484z"></path>
                                </svg>
                            </button>
                        </div>
                        <form onSubmit={handleSubmit(onSubmit)} className="mx-auto max-w-xl">
                        
            <input type="text" placeholder='nom'{...register("nameEmp", { required: true })} className="block w-full mt-2 p-2 border rounded-md" />
            {errors.nameEmp && <span className="text-red-500">Le nom est obligatoire</span>}

            <input type="text" placeholder='prenom' {...register("surnameEmp", { required: true })} className="block w-full mt-2 p-2 border rounded-md" />
            {errors.surnameEmp && <span className="text-red-500">Le prenom est obligatoire</span>}


            <input type="email" placeholder='email' {...register("email", { required: true })} className="block w-full mt-2 p-2 border rounded-md" />
            {errors.email && <span className="text-red-500">La date est obligatoire</span>}

            <input type="date" {...register("birthday", { required: true})} className="block w-full mt-2 p-2 border rounded-md" placeholder="Nombre d'heures d'absence" />
            {errors.birthday && <span className="text-red-500">Le jour de naissance est obligatoire</span>}

            <select {...register("sexe", { required: true })} className="block w-full mt-2 p-2 border rounded-md">
                <option value="">Choisir le genre</option>
                <option value="Feminin">Feminin</option>
                <option value="Masculin">Masculin</option>
            </select>
            {errors.sexe && <span className="text-red-500">Le genre est obligatoire</span>}

            <label htmlFor="">Telephone</label>
            <input type="number"  {...register("Tel", { required: true })} className="block w-full mt-2 p-2 border rounded-md" />
            {errors.Tel && <span className="text-red-500">Le telephone est obligatoire</span>}

            <input type="text" placeholder='poste' {...register("poste", { required: true })} className="block w-full mt-2 p-2 border rounded-md" />
            {errors.poste && <span className="text-red-500">Le poste est obligatoire</span>}

            <input type="number" placeholder='salaire' {...register("salaire", { required: true })} className="block w-full mt-2 p-2 border rounded-md" />
            {errors.salaire && <span className="text-red-500">Le salaire est obligatoire</span>}

            
               

            <div className='flex items-center'>
                <button type="submit" className="block mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Ajouter</button>
                <button type="reset" className="block mt-4 mx-auto px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Annuler</button>
            </div>
                
        </form>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Employee;
