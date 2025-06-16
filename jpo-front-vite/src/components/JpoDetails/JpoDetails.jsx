import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import "./JpoDetails.css"; // Assurez-vous d'avoir ce fichier CSS pour le style

const cityImages = {
  Marseille: "/images/marseille.jpg",
  Paris: "/images/paris.jpg",
  Cannes: "/images/cannes.jpg",
  Martigues: "/images/martigues.jpg",
};

export default function JpoDetails() {
  const { id } = useParams();
  const [jpo, setJpo] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    fetch("/api/jpo.php?id=" + id)
      .then((res) => {
        if (!res.ok) throw new Error("Erreur lors du chargement");
        return res.json();
      })
      .then((data) => {
        setJpo(data);
        setLoading(false);
      })
      .catch(() => {
        setError("Impossible de charger la JPO");
        setLoading(false);
      });
  }, [id]);

  const handleRegister = () => {
    const user = JSON.parse(localStorage.getItem("user"));
    const user_id = user?.id;
    const open_day_id = id;

    console.log("user_id utilisé pour inscription :", user_id);

    fetch("/api/register_jpo.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id, open_day_id }),
    })
      .then((res) => res.json())
      .then((data) => {
        console.log(data); // ← Ajoute ceci pour voir la vraie réponse du backend
      });
  };

  if (loading) {
    return (
      <div className="jpo-details">
        <div className="jpo-details-card">
          <p>Chargement...</p>
        </div>
      </div>
    );
  }
  if (error) return <div style={{ color: "red" }}>{error}</div>;
  if (!jpo) return null;

  console.log(jpo);

  const imageSrc = cityImages[jpo.city] || "/images/default.jpg";

  return (
    <div className="jpo-details">
      <div
        className="jpo-details-card"
        style={{
          maxWidth: 500,
          margin: "2rem auto",
          boxShadow: "0 2px 8px #ccc",
          borderRadius: 12,
          padding: 24,
          background: "#fff",
        }}
      >
        <h2 style={{ textAlign: "center" }}>{jpo.title}</h2>

        <p>
          <strong>Ville :</strong> {jpo.city}
        </p>
        <p>
          <strong>Adresse :</strong> {jpo.address}
        </p>
        <p>
          <strong>Date :</strong> {new Date(jpo.event_date).toLocaleString()}
        </p>
        <p>
          <strong>Description :</strong> {jpo.description}
        </p>
        <p>
          <strong>Capacité :</strong> {jpo.capacity}
        </p>
        <button
          onClick={handleRegister}
          style={{
            display: "block",
            margin: "2rem auto 0 auto",
            padding: "0.75rem 2rem",
            background: "#007bff",
            color: "#fff",
            border: "none",
            borderRadius: 6,
            fontSize: "1rem",
            cursor: "pointer",
          }}
        >
          S'inscrire à cette JPO
        </button>
        {/* Section commentaires à ajouter ici */}
      </div>
    </div>
  );
}
