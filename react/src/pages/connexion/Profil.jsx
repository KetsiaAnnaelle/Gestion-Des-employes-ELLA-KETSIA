import React, {useEffect,useState} from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';
// import Header from '../../component/Header';
import.meta.env.VITE_URL_Image;
// import BarreLateraleEtud from '../../component/BarreLateraleEtud';

const Profil = () => {

    const [user, setuser] = useState({})

     //Recuperer d'un utilisateur
    async function getUser() {
        try {
            const use = JSON.parse(localStorage.getItem('user'));
            setuser(use);
        } catch (error) {
            console.error('Error fetching profile photo:', error);
        }
    }

    console.log(user);

    useEffect(() => {
        getUser()
    }, [])


    return (
        <div>
        
            {/* <Header/> */}
            <div>
                
                <main id="main" className="main">

                    <div className="pagetitle">
                        <h1>Profile</h1>
                        <nav>
                            <ol className="breadcrumb">
                            <li className="breadcrumb-item"><Link to="/">Home</Link></li>
                            <li className="breadcrumb-item">Users</li>
                            <li className="breadcrumb-item active">Profile</li>
                            </ol>
                        </nav>
                    </div>
                    {/* <!-- End Page Title --> */}

                    <section className="section profile">
                        <div className="row">
                            <div className="col-xl-4">
                                <div className="card" style={{ height:'100%' }}>
                                    <div className="card-body profile-card pt-4 d-flex flex-column align-items-center">
                                        <img className='rounded-circle my-xxl-4' width={'250px'} height={'350px'} src={`${import.meta.env.VITE_URL_Image}/uploadImage/${user.profil}`} alt="profil"/>
                                        {/* <img src="assets/img/profile-img.jpg" alt="Profile" className="rounded-circle"/> */}
                                        {/* <img className='rounded-circle' width={'40px'} height={'80px'} src={`http://localhost:8000/uploadImage/${JSON.parse(localStorage.getItem('user')).profil}`} alt="profil"/> */}
                                        <h2>{user.name}</h2>
                                    </div>
                                </div>
                            </div>

                            <div className="col-xl-8">
                                <div className="card">
                                    <div className="card-body pt-3">
                                    {/* <!-- Bordered Tabs --> */}
                                        <ul className="nav nav-tabs nav-tabs-bordered">

                                            {/* <li className="nav-item">
                                                <button className="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                            </li> */}

                                            <li className="nav-item">
                                                <button className="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                                            </li>

                                            <li className="nav-item">
                                                <button className="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                                            </li>

                                            <li className="nav-item">
                                                <button className="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                                            </li>

                                        </ul>
                                        <div className="tab-content pt-2">

                                            <div className="tab-pane fade profile-edit pt-3" id="profile-edit">

                                                {/* <!-- Profile Edit htmlForm --> */}
                                                <form>
                                                    <div className="row mb-3">
                                                        <label htmlfor="profileImage" className="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                                        <div className="col-md-8 col-lg-9">
                                                            {/* <img src="assets/img/profile-img.jpg" alt="Profile"/> */}
                                                            <img width={'150px'} height={'120px'} src={`http://localhost:8000/uploadImage/${user.profil}`} alt="profil"/>
                                                            <div className="pt-2">
                                                                <a href="#" className="btn btn-primary btn-sm me-3" type='file' title="Upload new profile image"><i className="bi bi-upload"></i></a>
                                                                <a href="#" className="btn btn-danger btn-sm" title="Remove my profile image"><i className="bi bi-trash"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div className="row mb-3">
                                                        <label htmlfor="fullName" className="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                                        <div className="col-md-8 col-lg-9">
                                                            <input name="fullName" type="text" className="form-control" id="fullName" defaultValue={user.name}/>
                                                        </div>
                                                    </div>

                                                    {/* <div className="row mb-3">
                                                        <label htmlfor="Job" className="col-md-4 col-lg-3 col-form-label">Job</label>
                                                        <div className="col-md-8 col-lg-9">
                                                            <input name="job" type="text" className="form-control" id="Job" value="Web Designer"/>
                                                        </div>
                                                    </div> */}

                                                    <div className="row mb-3">
                                                        <label htmlfor="Email" className="col-md-4 col-lg-3 col-form-label">Email</label>
                                                        <div className="col-md-8 col-lg-9">
                                                            <input name="email" type="email" className="form-control" id="Email" defaultValue={user.email}/>
                                                        </div>
                                                    </div>

                                                    <div className="text-center">
                                                        <button type="submit" className="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                                {/* <!-- End Profile Edit htmlForm --> */}

                                            </div>

                                            <form action="">

                                                <div className="tab-pane fade pt-3" id="profile-change-password">
                                                {/* <!-- Change Password htmlForm --> */}
                                                    <form>

                                                        <div className="row mb-3">
                                                            <label htmlfor="currentPassword" className="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                                            <div className="col-md-8 col-lg-9">
                                                                <input name="password" type="password" className="form-control" id="currentPassword"/>
                                                            </div>
                                                        </div>

                                                        <div className="row mb-3">
                                                            <label htmlfor="newPassword" className="col-md-4 col-lg-3 col-form-label">New Password</label>
                                                            <div className="col-md-8 col-lg-9">
                                                                <input name="newpassword" type="password" className="form-control" id="newPassword"/>
                                                            </div>
                                                        </div>

                                                        <div className="row mb-3">
                                                            <label htmlfor="renewPassword" className="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                                            <div className="col-md-8 col-lg-9">
                                                                <input name="renewpassword" type="password" className="form-control" id="renewPassword"/>
                                                            </div>
                                                        </div>

                                                        <div className="text-center">
                                                            <button type="submit" className="btn btn-primary">Change Password</button>
                                                        </div>
                                                    </form> {/* <!-- End Change Password htmlForm --> */}
                                                </div>
                                            </form>


                                        </div>{/* <!-- End Bordered Tabs --> */}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </main>
            {/* <!-- End #main --> */}
            </div>
    
        </div>
    );
};

export default Profil;