import React, { useEffect, useState } from 'react';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import Accueil from './Accueil';
import Register from './pages/connexion/Register';
import Login from './pages/connexion/Login';
// import Register from './pages/connexion/Register';
import { createContext } from 'react';
import Employee from './pages/Employee';
import Profil from './pages/connexion/Profil';
import UpdateEmployee from './pages/UpdateEmployee';
import Dashboard from './pages/Dashboard';
export const USER = createContext();

const App = () => {


  const [user, setUser] = useState(JSON.parse(localStorage.getItem('user')) || []);

  const [token, settoken] = useState(localStorage.getItem('token'));

  useEffect(() => {
    console.log(localStorage.setItem('user',JSON.stringify(user)))
    console.log(token)
  }, [user])


  return (
    <USER.Provider value={[user, setUser, token, settoken]}>

      <BrowserRouter>
        <Routes>
          <Route path='/' element={<Accueil/>}/>
          <Route path='/employee' element={<Employee/>}/>
          <Route path='/update/:id' element={< UpdateEmployee/>} />
          <Route path='/dashboard' element={< Dashboard/>} />

          {/* Connexion */}
          <Route path='/login' element={< Login/>} />
            <Route path='/register' element={< Register/>} />
            <Route path='/profil' element={< Profil/>} />

            {/* <Route path='*' element={<Error />} /> */}

        </Routes>
      </BrowserRouter>
    </USER.Provider>
  );
};

export default App;