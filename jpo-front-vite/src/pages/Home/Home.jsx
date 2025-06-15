import React from "react";
import { Link } from "react-router-dom";
import cannes from "../../assets/cities/cannes.jpg";
import marseille from "../../assets/cities/marseille.jpg";
import martigues from "../../assets/cities/martigues.jpg";
import paris from "../../assets/cities/paris.jpg";
import "./Home.css";

const villes = [
  { name: "Cannes", img: cannes, path: "/city/Cannes" },
  { name: "Martigues", img: martigues, path: "/city/Martigues" },
  { name: "Paris", img: paris, path: "/city/Paris" },
  { name: "Marseille", img: marseille, path: "/city/Marseille" },
];

export default function Home() {
  return (
    <div>
      <div className="villes-grid">
        {villes.map((ville) => (
          <Link key={ville.name} to={ville.path} className="ville-card">
            <img src={ville.img} alt={ville.name} />
            <span>{ville.name}</span>
          </Link>
        ))}
      </div>
    </div>
  );
}
