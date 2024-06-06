import React, {useEffect, useState} from 'react';
import '/public/assets/css/index.css'
import { Link, useNavigate } from "react-router-dom";
import axios from 'axios';
// import { useForm } from "react-hook-form";
import swal from 'sweetalert';
import.meta.env.VITE_URL;
import Swal from 'sweetalert2'
import { FaEye, FaEyeSlash } from 'react-icons/fa';
// import { useStateContext } from './ContextProvider';



const Register = () => {

    // const { register, handleSubmit,reset, formState: { errors } } = useForm();

    const [name, setname] = useState('')
    const [email, setemail] = useState('')
    const [password, setpassword] = useState('')
    const [passwordconfirm, setpasswordconfirm] = useState('')
    const [profil, setprofil] = useState('')

    const [role, setrole] = useState('')
    

    const [validationErrors, setvalidationErrors] = useState({})

    const history= useNavigate()



    const Register = async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('name',name);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('profil', profil);
        formData.append('role', role);

        if (password === passwordconfirm) {
            
            axios.post(`${import.meta.env.VITE_URL}/register`,formData)
            
            .then(function (response) {
                // const {access_token } = response.data
                console.log(response.data)
                // JSON.stringify(response.data)

                // localStorage.setItem('token', access_token)

                // setUser(response.user.name)
                // setToken(response.token)
                
                // reset('')

                setemail('')
                setrole('')
                setname('')
                setpassword('')
                setpasswordconfirm('')

                swal({
                    title: "Compte Crée Avec Succès !!!",
                    text: "You clicked the button!",
                    icon: "success",
                    button: "OK",
                    timer: 2000
                });

                
                history('/login')
            })
            .catch(function(error)  {

                //gestion des erreurs de validation
    
                if (error.response && error.response.status ===422) {
                    const errors = error.response.data.errors;
                    
                    console.log(errors);
                    setvalidationErrors(errors)
                    
                    console.log(validationErrors);
                } 
                // else {
                    
                //     console.error('Erreur non geres : ',error.message);
                // }
            })
        }
        else{

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Les mots de passe ne sont pas correct !!!',
                timer: 2000
                // footer: '<a href="">Why do I have this issue?</a>'
            })
        }

    }

    const [showPassword, setShowPassword] = useState(false);
    const [showPassword1, setShowPassword1] = useState(false);

    const toggleShowPassword = () => {
        setShowPassword(!showPassword);
    };

    const toggleShowPassword1 = () => {
        setShowPassword(!showPassword1);
    };

    return (

        <div className="min-h-screen min-w-lg flex items-center justify-center bg-gray-100">
        <div className="max-w-md w-full p-6 bg-white shadow-md rounded-md">
            <img src="public/assets/img/logo-employe.avif" alt="Logo" className="mx-auto mb-6" />
    
            <form action="post" onSubmit={(e) => {Register(e)}} className='form' encType='multipart/form-data'>
                <p className="text-2xl font-bold text-center mb-4">INSCRIVEZ-VOUS</p>
    
                <div className="mb-4">
                    <input type="text" className="form-control input text-center" id="name" placeholder="Nom" value={name} onChange={(e)=>setname(e.target.value)}/>
                    {validationErrors.name && (
                        <div className="error-message text-red-500 text-center">{validationErrors.name[0]}</div>
                    )}
                </div>
    
                <div className="mb-4">
                    <input type="email" className="form-control input text-center" id="email" placeholder="Email" value={email} onChange={(e)=>setemail(e.target.value)}/>
                    {validationErrors.email && (
                        <div className="error-message text-red-500 text-center">{validationErrors.email[0]}</div>
                    )}
                </div>
    
                <div className="flex flex-row gap-2">
                    <label htmlFor="image" className="text-center block mb-1">Profil</label>
                    <input type="file" className="form-control input" placeholder='photo de profil' id='image' onChange={(e)=>setprofil(e.target.files[0])}/>
                    {validationErrors.profil && (
                        <div className="error-message text-red-500 text-center">{validationErrors.profil[0]}</div>
                    )}
                </div>
    
                <div className="my-2" style={{ position: 'relative', width: '300px' }}>
                    <input type={showPassword ? 'text' : 'password'} className="form-control input text-center" id="password" placeholder="Entrer votre mot de passe" value={password} onChange={(e)=>setpassword(e.target.value)}/>
                    
                    <div
                        onClick={toggleShowPassword}
                        style={{
                        position: 'absolute',
                        right: '10px',
                        top: '50%',
                        transform: 'translateY(-50%)',
                        cursor: 'pointer'
                        }}
                    >
                        {showPassword ? <FaEyeSlash size={20} /> : <FaEye size={20} />}
                    </div>
                    {validationErrors.password && (
                        <div className="error-message text-red-500 text-center">{validationErrors.password[0]}</div>
                    )}
                </div>
    
                <div className="mb-4" style={{ position: 'relative', width: '300px' }}>
                    <input type={showPassword ? 'text' : 'password'} className="form-control input text-center" id="password" placeholder="Confirmer votre mot de passe" value={passwordconfirm} onChange={(e)=>setpasswordconfirm(e.target.value)}/>
                    
                    <div
                    onClick={toggleShowPassword1}
                    style={{
                    position: 'absolute',
                    right: '10px',
                    top: '50%',
                    transform: 'translateY(-50%)',
                    cursor: 'pointer'
                    }}
                >
                    {showPassword ? <FaEyeSlash size={20} /> : <FaEye size={20} />}
                </div>
                {validationErrors.passwordconfirm && (
                        <div className="error-message text-red-500 text-center">{validationErrors.passwordconfirm[0]}</div>
                    )}
                </div>
    
                <div className="mb-4">
                <select className="block w-full mt-2 p-2 border rounded-md" placeholder="Entrer le role de l'uitlisateur" value={role} onChange={(e)=>setrole(e.target.value)}>
                    <option value="">Creer le compte en tant que</option>
                    <option value="admin">admin</option>
                    <option value="employe">employe</option>
                </select>
                {validationErrors.role && (
                    <div className="error-message text-red-500 text-center">{validationErrors.role[0]}</div>
                )}
                </div>
    
                <div className="mb-4">
                    <p className="text-center">Déjà un compte? <Link to="/login" className="link-underline-primary"> Connectez-vous</Link></p>
                </div>
    
                <button type="submit" className="text-gray-50 w-full bg-gray-500 p-2 rounded-md">Inscription</button>
            </form>
        </div>
    </div>
    
    
    

    );
};



export default Register;