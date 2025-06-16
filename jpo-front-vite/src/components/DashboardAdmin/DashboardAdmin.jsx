import React, { useEffect, useState } from "react";

export default function DashboardAdmin() {
  const [data, setData] = useState(null);
  const [error, setError] = useState("");

  // Exemple : récupération du user connecté
  const user = JSON.parse(localStorage.getItem("user"));
  const role = user?.role;

  // Si pas connecté ou pas director, on bloque l'accès
  if (!user || user.role !== "director") {
    return <div style={{ color: "red" }}>Accès interdit</div>;
  }

  useEffect(() => {
    fetch("http://localhost:8000/jpo-connect/backend/public/api/jpos")
      .then((res) => res.json())
      .then((data) => setData(data));
  }, []);

  if (error) return <div style={{ color: "red" }}>{error}</div>;
  if (!data) return <div>Chargement...</div>;

  return (
    <div className="dashboard-admin">
      <h2>Dashboard Admin (Directeur)</h2>
      <h3>JPOs</h3>
      <ul>
        {data.jpos.map((jpo) => (
          <li key={jpo.id}>
            {jpo.title} ({jpo.event_date})
          </li>
        ))}
      </ul>
      <h3>Utilisateurs</h3>
      <ul>
        {data.users.map((user) => (
          <li key={user.id}>
            {user.first_name} {user.last_name} - {user.email} ({user.role})
          </li>
        ))}
      </ul>
    </div>
  );
}

export function JpoList() {
  const [jpos, setJpos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    fetch("/api/jpos.php")
      .then((res) => {
        if (!res.ok) throw new Error("Erreur serveur");
        return res.json();
      })
      .then((data) => {
        setJpos(Array.isArray(data) ? data : []);
        setLoading(false);
      })
      .catch(() => {
        setError("Impossible de charger les JPO");
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Chargement...</div>;
  if (error) return <div className="error">{error}</div>;

  return (
    <div>
      <h2>Liste des JPO</h2>
      <ul>
        {jpos.length === 0 && <li>Aucune JPO trouvée.</li>}
        {jpos.map((jpo) => (
          <li key={jpo.id}>
            {jpo.title} – {new Date(jpo.event_date).toLocaleDateString()}
          </li>
        ))}
      </ul>
    </div>
  );
}
