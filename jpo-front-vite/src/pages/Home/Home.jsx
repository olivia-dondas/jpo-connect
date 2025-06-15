import React from "react";
import { Link } from "react-router-dom";
import "./Home.css";

const villes = [
  { name: "Cannes", img: "/images/cannes.jpg", path: "/city/Cannes" },
  { name: "Martigues", img: "/images/martigues.jpg", path: "/city/Martigues" },
  { name: "Paris", img: "/images/paris.jpg", path: "/city/Paris" },
  { name: "Marseille", img: "/images/marseille.jpg", path: "/city/Marseille" },
];

export default function Home() {
  return (
    <div className="home-container">
      <h1 className="home-title">Découvrez nos villes partenaires</h1>
      <p className="home-subtitle">Trouvez les JPO près de chez vous</p>

      <div className="villes-grid">
        {villes.map((ville) => (
          <Link key={ville.name} to={ville.path} className="ville-card">
            <div className="image-container">
              <img src={ville.img} alt={ville.name} />
              <div className="overlay"></div>
            </div>
            <h3>{ville.name}</h3>
          </Link>
        ))}
      </div>
    </div>
  );
}
