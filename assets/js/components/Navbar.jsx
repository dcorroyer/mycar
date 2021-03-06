import React, {useContext} from 'react';
import {NavLink} from 'react-router-dom';
import AuthContext from '../contexts/AuthContext';
import AuthAPI from '../services/authAPI';
import {toast} from 'react-toastify';


const Navbar = ({history}) => {

    const {isAuthenticated, setIsAuthenticated} = useContext(AuthContext);

    const handleLogout = () => {
        if (confirm("Voulez-vous vous déconnecter ?")) {
            AuthAPI.logout();
            setIsAuthenticated(false);
            toast.info("Vous êtes déconnecté !");
            history.push("/");
        }
    };

    return (
        <nav className="navbar navbar-expand-lg navbar-light bg-light">
            <NavLink className="navbar-brand" to="/">Maintain Your Car</NavLink>
            <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03"
                    aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
                <span className="navbar-toggler-icon"/>
            </button>

            <div className="collapse navbar-collapse" id="navbarColor03">
                <ul className="navbar-nav mr-auto">
                    <li className="nav-item">
                        <NavLink className="nav-link" to="/vehicules">
                            Mes véhicules
                        </NavLink>
                    </li>
                    <li className="nav-item">
                        <NavLink className="nav-link" to="/maintenances">
                            Mes maintenances
                        </NavLink>
                    </li>
                </ul>
                <ul className="navbar-nav ml-auto">
                    {(!isAuthenticated && (
                        <>
                            <li className="nav-item">
                                <NavLink className="nav-link" to="/register">
                                    Inscription
                                </NavLink>
                            </li>
                            <li className="nav-item">
                                <NavLink className="btn btn-success ml-1" to="/login">
                                    Connexion
                                </NavLink>
                            </li>
                        </>
                    )) || (
                        <li className="nav-item">
                            <button onClick={handleLogout} className="btn btn-danger ml-1">
                                Déconnexion
                            </button>
                        </li>
                    )}
                </ul>
            </div>
        </nav>
    );
};

export default Navbar;