import React, { useEffect, useState } from 'react';
import Header from '../components/Header';
import { useForm } from 'react-hook-form';
import axios from 'axios';
import swal from 'sweetalert';
import { Link, useNavigate, useParams } from 'react-router-dom';
import '/public/assets/css/index.css';

const UpdateEmployee = () => {
    const { register, handleSubmit, reset, setValue, formState: { errors } } = useForm({});
    const [employee, setEmployee] = useState({});
    const { id } = useParams(); // Extraire correctement le paramètre id
    const navigate = useNavigate(); // Utilisation correcte de useNavigate

    const getInfoEmploye = async () => {
        try {
            const response = await axios.get(`${import.meta.env.VITE_URL}/edit-employe/${id}`);
            console.log('Response from API:', response.data); // Log pour déboguer
            setEmployee(response.data);

            // Remplir les champs du formulaire avec les valeurs récupérées
            for (const key in response.data) {
                setValue(key, response.data[key]);
            }
        } catch (error) {
            console.error('Error fetching employee data:', error); // Affichez l'erreur dans la console pour le débogage
        }
    };

    useEffect(() => {
        getInfoEmploye();
    }, [id]);

    const updateEmploye = async (data) => {
        try {
            await axios.put(`${import.meta.env.VITE_URL}/edit-employe/${id}`, data);
            swal({
                title: "Modification Réussie !!!",
                text: "Vous avez cliqué sur le bouton!",
                icon: "success",
                button: "OK",
                timer: 2000
            });
            navigate('/employee');
        } catch (error) {
            console.error('Error updating employee:', error); // Affichez l'erreur dans la console pour le débogage
        }
    };

    return (
        <div>
            <Header />
            <h2 className='flex justify-center font-bold text-yellow-300 shadow-sm my-5 py-4 bg-yellow-50 min-w-xl' style={{ marginTop: '6.5rem' }}>
                MODIFIER LES INFORMATIONS DE: {employee.nameEmp}
            </h2>
            <div className="container mx-auto mt-4">
                <form onSubmit={handleSubmit(updateEmploye)} className="mx-auto max-w-xl form">
                    <input 
                        type="text" 
                        placeholder='nom' 
                        {...register("nameEmp", { required: true })} 
                        className="block w-full mt-2 p-2 border input" 
                    />
                    {errors.nameEmp && <span className="text-red-500">Le nom est obligatoire</span>}

                    <input 
                        type="text" 
                        placeholder='prenom' 
                        {...register("surnameEmp", { required: true })} 
                        className="block w-full mt-2 p-2 border input" 
                    />
                    {errors.surnameEmp && <span className="text-red-500">Le prénom est obligatoire</span>}

                    <input 
                        type="email" 
                        placeholder='email' 
                        {...register("email", { required: true })} 
                        className="block w-full mt-2 p-2 border input" 
                    />
                    {errors.email && <span className="text-red-500">L'email est obligatoire</span>}

                    <input 
                        type="date" 
                        {...register("birthday", { required: true })} 
                        className="block w-full mt-2 p-2 border input" 
                    />
                    {errors.birthday && <span className="text-red-500">Le jour de naissance est obligatoire</span>}

                    <select 
                        {...register("sexe", { required: true })} 
                        className="block w-full mt-2 p-2 border input" 
                    >
                        <option value="">Choisir le genre</option>
                        <option value="Feminin">Feminin</option>
                        <option value="Masculin">Masculin</option>
                    </select>
                    {errors.sexe && <span className="text-red-500">Le genre est obligatoire</span>}

                    <input 
                        type="number"  
                        {...register("Tel", { required: true })} 
                        className="block w-full mt-2 p-2 border input" 
                        placeholder='Tel'
                    />
                    {errors.Tel && <span className="text-red-500">Le téléphone est obligatoire</span>}

                    <input 
                        type="text" 
                        placeholder='poste' 
                        {...register("poste", { required: true })} 
                        className="block w-full mt-2 p-2 border input" 
                    />
                    {errors.poste && <span className="text-red-500">Le poste est obligatoire</span>}

                    <input 
                        type="number" 
                        placeholder='salaire' 
                        {...register("salaire", { required: true })} 
                        className="block w-full mt-2 p-2 border input" 
                    />
                    {errors.salaire && <span className="text-red-500">Le salaire est obligatoire</span>}

                    <div className='flex items-center'>
                        <button 
                            type="submit" 
                            className="block mt-4 px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Modifier
                        </button>
                        <Link to="/employee" className='mx-auto'>
                            <button 
                                className="block mt-4 px-4 py-2 bg-yellow-500 text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                            >
                                Annuler
                            </button>
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default UpdateEmployee;
