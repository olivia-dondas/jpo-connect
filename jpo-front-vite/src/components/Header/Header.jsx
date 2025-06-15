// filepath: /Applications/MAMP/htdocs/jpo-connect/jpo-front-vite/src/components/Header.jsx
import React from "react";
import { Link } from "react-router-dom";
import logo from "../../assets/logo.png";
import "./Header.css";

export default function Header() {
  return (
    <header className="header">
      <img src={logo} alt="Logo La Plateforme" className="header-logo" />
      <nav className="header-nav">
        <Link to="/">Accueil</Link>
        <Link to="/login">Connexion</Link>
        <Link to="/register">Inscription</Link>
        <Link to="/profile">Profil</Link>
        <Link to="/admin">Admin</Link>
        <Link to="/moderation">Modération</Link>
      </nav>
    </header>
  );
}
