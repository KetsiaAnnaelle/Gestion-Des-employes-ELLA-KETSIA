import React from 'react';
import { Link } from 'react-router-dom';
import { FaGraduationCap } from 'react-icons/fa';
import { PiMoneyBold } from 'react-icons/pi';
import { BiUser, BiMoney, BiSolidBookAlt, BiChat } from 'react-icons/bi';
import { BsFillBriefcaseFill, BsSearch } from 'react-icons/bs';
import { AiOutlineLineChart } from 'react-icons/ai';
import Header from './components/Header.jsx';
import Carousel from './components/Carousel.jsx';

const Accueil = () => {
    return (
        <main>
            <Header />
            <div className="container mx-auto mt-4">
                <div className="flex justify-center">
                    <div className="w-full md:w-1/2">
                        <form>
                            <div className="relative mb-3">
                                <input
                                    type="text"
                                    placeholder="Entrer un module"
                                    className="form-input w-full p-4 rounded-lg border-2 border-gray-300"
                                />
                                <BsSearch className="absolute top-1/2 right-4 transform -translate-y-1/2 text-xl" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div className="container mx-auto">
                <Carousel/>
            </div>
            <div className="container mx-auto mt-10">
                <div className="flex flex-wrap justify-center gap-3">
                    <div className="w-full md:w-1/4 lg:w-1/6 mb-6">
                        <Link to='/employee' className='text-center text-dark'>
                            <div className="bg-gray-200 p-6 rounded-lg shadow-lg text-center">
                                <FaGraduationCap size={70} className="mx-auto text-yellow-300" />
                                <p className="mt-4 text-dark">Employe</p>
                            </div>
                        </Link>
                    </div>

                    <div className="w-full md:w-1/4 lg:w-1/6 mb-6">
                        <Link to='/dashboard' className='text-center text-dark'>
                            <div className="bg-gray-200 p-6 rounded-lg shadow-lg text-center">
                                <AiOutlineLineChart size={70} className="mx-auto text-yellow-300" />
                                <p className="mt-4 text-dark">Dashboard</p>
                            </div>
                        </Link>
                    </div>

                    <div className="w-full md:w-1/4 lg:w-1/6 mb-6">
                        <Link to='/chat' className='text-center text-dark'>
                            <div className="bg-gray-200 p-6 rounded-lg shadow-lg text-center">
                                <BiChat size={70} className="mx-auto text-yellow-300" />
                                <p className="mt-4 text-dark">Chat</p>
                            </div>
                        </Link>
                    </div>
                   
                    
                </div>
            </div>
        </main>
    );
};

export default Accueil;
