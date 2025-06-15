import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import "./JpoDetails.css";

export default function JpoDetails() {
  const { id } = useParams();
  const [jpo, setJpo] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    fetch(`http://localhost:8000/api/jpos/${id}`)
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

  if (loading) return <div>Chargement...</div>;
  if (error) return <div style={{ color: "red" }}>{error}</div>;
  if (!jpo) return null;

  return (
    <div className="jpo-details">
      <h2>{jpo.title}</h2>
      <p>
        <strong>Date :</strong> {jpo.event_date}
      </p>
      <p>
        <strong>Ville :</strong> {jpo.city}
      </p>
      <p>
        <strong>Description :</strong> {jpo.description}
      </p>
      {/* Ajoute ici d'autres infos (image, intervenants, etc.) */}
      <button>S'inscrire à cette JPO</button>
    </div>
  );
}
