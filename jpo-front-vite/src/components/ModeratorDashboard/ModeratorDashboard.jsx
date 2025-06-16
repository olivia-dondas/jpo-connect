import React, { useEffect, useState } from "react";

export default function ModeratorDashboard() {
  const [jpos, setJpos] = useState([]);
  const [users, setUsers] = useState([]);
  const [comments, setComments] = useState([]);
  const [error, setError] = useState("");

  // Récupération du user connecté
  const user = JSON.parse(localStorage.getItem("user"));

  // Si pas connecté ou pas director/moderator, on bloque l'accès
  if (!user || (user.role !== "director" && user.role !== "moderator")) {
    return <div style={{ color: "red" }}>Accès interdit</div>;
  }

  // Charger les JPO
  useEffect(() => {
    fetch("/api/open_days.php")
      .then((res) => res.json())
      .then(setJpos)
      .catch(() => setError("Erreur chargement JPO"));
  }, []);

  // Charger les utilisateurs
  useEffect(() => {
    fetch("/api/users.php")
      .then((res) => res.json())
      .then(setUsers)
      .catch(() => setError("Erreur chargement utilisateurs"));
  }, []);

  // Charger les commentaires à modérer
  useEffect(() => {
    fetch("/api/moderation_comments.php")
      .then((res) => res.json())
      .then(setComments)
      .catch(() => setError("Erreur chargement commentaires"));
  }, []);

  // Modérer un commentaire
  const handleModerate = (id, status) => {
    fetch("/api/moderate_comment.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, status }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setComments(comments.filter((c) => c.id !== id));
        }
      });
  };

  if (error) return <div style={{ color: "red" }}>{error}</div>;

  return (
    <div
      className="moderator-dashboard"
      style={{
        maxWidth: 900,
        margin: "2rem auto",
        background: "#fff",
        borderRadius: 12,
        boxShadow: "0 2px 8px #ccc",
        padding: 24,
      }}
    >
      <h2>Dashboard Modération</h2>

      <h3>Commentaires à modérer</h3>
      <table
        style={{
          width: "100%",
          borderCollapse: "collapse",
          marginBottom: "2rem",
        }}
      >
        <thead>
          <tr>
            <th>JPO</th>
            <th>Auteur</th>
            <th>Commentaire</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          {comments.length === 0 && (
            <tr>
              <td colSpan={5} style={{ textAlign: "center" }}>
                Aucun commentaire à modérer
              </td>
            </tr>
          )}
          {comments.map((c) => (
            <tr key={c.id}>
              <td>{c.title || c.open_day_id}</td>
              <td>
                {c.first_name} {c.last_name}
              </td>
              <td>{c.content}</td>
              <td>{new Date(c.created_at).toLocaleString()}</td>
              <td>
                <button
                  onClick={() => handleModerate(c.id, "approved")}
                  style={{
                    background: "#28a745",
                    color: "#fff",
                    marginRight: 8,
                  }}
                >
                  Approuver
                </button>
                <button
                  onClick={() => handleModerate(c.id, "rejected")}
                  style={{ background: "#dc3545", color: "#fff" }}
                >
                  Rejeter
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      <h3>JPOs</h3>
      <ul>
        {jpos.length === 0 && <li>Aucune JPO trouvée.</li>}
        {jpos.map((jpo) => (
          <li key={jpo.id}>
            {jpo.title} – {new Date(jpo.event_date).toLocaleDateString()}
          </li>
        ))}
      </ul>

      <h3>Utilisateurs</h3>
      <ul>
        {users.length === 0 && <li>Aucun utilisateur trouvé.</li>}
        {users.map((u) => (
          <li key={u.id}>
            {u.first_name} {u.last_name} - {u.email} ({u.role})
          </li>
        ))}
      </ul>
    </div>
  );
}
