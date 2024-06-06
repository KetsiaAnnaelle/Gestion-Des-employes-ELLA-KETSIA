import React, { useEffect, useState } from 'react';
import { FaUserGraduate, FaFemale, FaMale } from 'react-icons/fa';
import { Bar } from 'react-chartjs-2';
import Header from '../components/Header';
import axios from 'axios';
import.meta.env.VITE_URL;

import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    LineElement,
    PointElement,
    Title,
    Tooltip,
    Legend, ArcElement, Colors,
} from 'chart.js';

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    LineElement,
    PointElement,
    Title,
    Tooltip,
    Legend,
    // label
);

const Dashboard = () => {
  const [employee, setEmployee] = useState([]);
  const [employeePerMonth, setEmployeePerMonth] = useState([]);

    async function getEmployee() {
        try {
            const response = await axios.get(`${import.meta.env.VITE_URL}/employe`);
            setEmployee(response.data);
        } catch (error) {
            console.error(error);
        }
    }

    async function EmployeePerMonth() {
        try {
            const response = await axios.get(`${import.meta.env.VITE_URL}/employee_Per_Month`);
            setEmployeePerMonth(response.data.employeePerMonth);
        } catch (error) {
            console.error(error);
        }
    }

    const options = {
        indexAxis: 'x',
        responsive: true,
        plugins: {
        Legend: {
            position: 'top',
        },
        Title: {
            display: true,
        },
        },
        Animations: {
        tension: {
            duration: 0,
            loop: false,
        },
        },
    };

    const months = [
        'Jan', 'Fev', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Dec'
    ];

    const Barlabels = employeePerMonth.map(result => months[result.month - 1]);
    const Barvalues = employeePerMonth.map(result => result.count);

    const Bardata = {
        labels: Barlabels,
        datasets: [
        {
            label: ["Nombre d'Employés Recrutés par Mois"],
            data: Barvalues,
            backgroundColor: 'rgba(63, 82, 235, 0.5)',
            borderColor: 'blue',
            borderWidth: 1
        }
        ],
    };

    const totalEmployees = employee.length;
    const femaleCount = employee.filter(emp => emp.sexe === 'Feminin').length;
    const maleCount = employee.filter(emp => emp.sexe === 'Masculin').length;


    useEffect(() => {
        getEmployee();
        EmployeePerMonth();
    }, []);


    if (JSON.parse(localStorage.getItem('user')).role !== 'admin') {

        // setInterval(() => {
        //     swal({
        //         title: "Impossible de consulter cette page car vous n'etes pas administrateur",
        //         text: "You clicked the button!",
        //         icon: "error",
        //         button: "OK",
        //     });
        // }, 3000);
        alert("Impossible de consulter cette page car vous n'etes pas administrateur")
        window.location.replace('/')
    }

    return (
        <section className="section dashboard">
        <Header />

        <div className="overflow-hidden whitespace-nowrap" style={{ marginTop: '6.5rem' }}>
            <div className="animate-marquee inline-block py-2 px-4 bg-yellow-100 text-black font-bold">
            TABLEAU DE BORD DE L'ENTREPRISE
            </div>
        </div>
        <div className="mx-auto" style={{ marginTop: '6.5rem'}}>
            <div className="w-full">
                <div className="flex justify-center">
                    <div className="w-8/12 text-center">
                    <p className="text-blue-500 text-2xl font-bold shadow-lg" style={{ boxShadow: '2px 2px 2px blue', width: '50%' }}>EMPLOYÉS</p>
                    </div>
                </div>

                <div className="mx-30 flex flex-wrap" style={{ marginTop: '6.5rem' }}>
                    <div className="w-full lg:w-1/4 md:w-1/2 p-2">
                        <div className="bg-white rounded-lg shadow-lg p-4">
                            <h5 className="text-yellow-500 font-bold">Total Employés</h5>
                            <div className="flex items-center">
                            <div className="bg-gray-200 rounded-full p-3">
                                <FaUserGraduate size={24} className='text-red-500'/>
                            </div>
                            <div className="pl-3">
                                <h6 className="text-xl font-bold">{totalEmployees}</h6>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div className="w-full lg:w-1/4 md:w-1/2 p-2">
                        <div className="bg-white rounded-lg shadow-lg p-4">
                            <h5 className="text-yellow-500 font-bold">Effectifs Femmes</h5>
                            <div className="flex items-center">
                            <div className="bg-gray-200 rounded-full p-3">
                                <FaFemale size={24} className='text-red-500'/>
                            </div>
                            <div className="pl-3">
                                <h6 className="text-xl font-bold">{femaleCount}</h6>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div className="w-full lg:w-1/4 md:w-1/2 p-2">
                        <div className="bg-white rounded-lg shadow-lg p-4">
                            <h5 className="text-yellow-500 font-bold">Effectifs Hommes</h5>
                            <div className="flex items-center">
                            <div className="bg-gray-200 rounded-full p-3">
                                <FaMale size={24} className='text-red-500'/>
                            </div>
                            <div className="pl-3">
                                <h6 className="text-xl font-bold">{maleCount}</h6>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div className="w-full lg:w-1/2 p-2">
                    <div className="bg-white rounded-lg shadow-lg p-4">
                        <h5 className="text-lg font-bold">Nombre d'emplois retenus par Mois</h5>
                        <div className="h-48">
                        <Bar data={Bardata} options={options} />
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    );
};

export default Dashboard;


