// filepath: /Applications/MAMP/htdocs/jpo-connect/jpo-front-vite/src/components/Header.jsx
import React from "react";
import { Link, useNavigate } from "react-router-dom";
import logo from "../../assets/logo.png";
import "./Header.css";

export default function Header() {
  const navigate = useNavigate();
  const user = JSON.parse(localStorage.getItem("user") || "null");
  const role = user?.role;

  const handleLogout = () => {
    localStorage.removeItem("user");
    localStorage.removeItem("token");
    navigate("/login");
  };

  return (
    <header className="header">
      <img src={logo} alt="Logo La Plateforme" className="header-logo" />
      <nav className="header-nav">
        <Link to="/">Accueil</Link>
        {!user && (
          <>
            <Link to="/login">Connexion</Link>
            <Link to="/register">Inscription</Link>
          </>
        )}
        {user && (
          <>
            <Link to="/profile">Profil</Link>
            {(role === "manager" || role === "director") && (
              <Link to="/admin">Admin</Link>
            )}
            {(role === "manager" || role === "director") && (
              <Link to="/moderation">Modération</Link>
            )}
            {user && role === "director" && (
              <Link to="/director-dashboard">Dashboard</Link>
            )}
            <button onClick={handleLogout} className="logout-btn">
              Déconnexion
            </button>
          </>
        )}
      </nav>
    </header>
  );
}
