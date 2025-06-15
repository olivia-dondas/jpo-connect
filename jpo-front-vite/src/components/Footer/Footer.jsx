import React from "react";
import { Link } from "react-router-dom";
import "./Footer.css";

export default function Footer() {
  return (
    <footer className="footer">
      <nav>
        <Link to="/">Accueil</Link>
        <Link to="/login">Connexion</Link>
        <Link to="/register">Inscription</Link>
        <Link to="/profile">Profil</Link>
        <Link to="/admin">Admin</Link>
        <Link to="/moderation">Modération</Link>
      </nav>
      <div className="footer-copy">
        &copy; {new Date().getFullYear()} La Plateforme - JPO
      </div>
    </footer>
  );
}
