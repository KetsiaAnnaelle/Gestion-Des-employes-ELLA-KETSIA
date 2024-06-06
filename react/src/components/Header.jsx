import React, { useEffect, useState } from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import { FaArrowCircleDown, FaArrowRight, FaUserCircle } from "react-icons/fa";
import axios from 'axios';

const Header = () => {
    const [user, setUser] = useState([]);
    const history = useNavigate();

    async function deconnexion(e) {
        e.preventDefault();
        try {
            localStorage.removeItem('user');
            history('/login');
            console.log('Deconnexion reussi');
        } catch (error) {
            console.log('Erreur de connexion', error);
        }
    }

    async function getUser() {
        try {
            const use = JSON.parse(localStorage.getItem('user'));
            setUser(use);
        } catch (error) {
            console.error('Error fetching profile photo:', error);
        }
    }

    const [isOpen, setIsOpen] = useState(false);

    const ToggleDropDown = () => {
        setIsOpen(!isOpen);
    };



    useEffect(() => {
        getUser();
    }, []);

    return (
        <header className="fixed-top flex items-center justify-between h-20 bg-white shadow-md px-4 fixed top-0 w-full py-4 z-20">
            <div className="flex items-center">
                <NavLink to="/" className="flex items-center">
                    <img src="/assets/img/logo-employe.avif" alt="Logo" className="" width={98} height={98}/>
                    <span className="ml-2 hidden lg:block text-lg font-semibold">Gestion des employés</span>
                </NavLink>
            </div>

            <nav className="flex items-center ml-auto">
                <ul className="flex items-center space-x-4">
                   

                    <li className="relative">
                       
                        <NavLink className="flex items-center text-gray-600" to="" data-bs-toggle="dropdown" >
                            {user.profil ? (
                                <img
                                    className="w-10 h-10 rounded-full"
                                    src={`http://localhost:8000/uploadImage/${user.profil}`}
                                    alt="Profile"
                                />
                            ) : (
                                <FaUserCircle className="w-10 h-10" />
                            )}
                            <span className="ml-2 hidden md:block">{user.name}</span>
                            <FaArrowCircleDown className='mx-2' onClick={ToggleDropDown} aria-controls="navbar-default" aria-expanded={isOpen ? "true" : "false"}/> 
                        
                        </NavLink> 
                        {/* className="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg" */}

                            <ul id="navbar-default" className={`${isOpen ? 'block' : 'hidden'} md:w-full absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg`} > 
                                {
                                    user.profil? ( 
                                        <div>
                                            <li className="px-4 py-2">
                                                <h6 className="font-semibold">{user.name}</h6>
                                            </li>

                                            <li className="border-t border-gray-200">
                                                <NavLink
                                                    onClick={(e) => deconnexion(e)}
                                                    className="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100"
                                                    to="">
                                                    <i className="bi bi-box-arrow-right mr-2"></i>
                                                    <span>Sign Out</span>
                                                </NavLink>
                                            </li>
                                        </div>
                                 
                                    ):

                                    (
                                        <li className="border-t border-gray-200">
                                            <NavLink
                                                className="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100"
                                                to="/login">
                                                <i className="bi bi-box-arrow-right mr-2"></i>
                                                <span>Sign In</span>
                                            </NavLink>
                                        </li>
                                    )
                                }
                            
                            </ul>
                        {/* </FaArrowRight> */}
                    </li>
                </ul>
            </nav>
        </header>
    );
};

export default Header;
