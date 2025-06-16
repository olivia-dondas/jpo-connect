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
  const [comments, setComments] = useState([]);
  const [newComment, setNewComment] = useState("");
  const [editId, setEditId] = useState(null);
  const [editContent, setEditContent] = useState("");
  const user = JSON.parse(localStorage.getItem("user"));

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

  // Charger les commentaires
  useEffect(() => {
    fetch(`/api/comments.php?open_day_id=${id}`)
      .then((res) => res.json())
      .then(setComments);
  }, [id]);

  const handleRegister = () => {
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

  // Ajouter un commentaire
  const handleAddComment = (e) => {
    e.preventDefault();
    if (!user) return;
    fetch("/api/add_comment.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        user_id: user.id,
        open_day_id: id,
        content: newComment,
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setNewComment("");
          fetch(`/api/comments.php?open_day_id=${id}`)
            .then((res) => res.json())
            .then(setComments);
        }
      });
  };

  // Supprimer un commentaire
  const handleDeleteComment = (commentId) => {
    if (!user) return;
    fetch(`/api/delete_comment.php?id=${commentId}&user_id=${user.id}`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setComments(comments.filter((c) => c.id !== commentId));
        }
      });
  };

  // Préparer l'édition
  const handleEditClick = (comment) => {
    setEditId(comment.id);
    setEditContent(comment.content);
  };

  // Valider l'édition
  const handleEditSubmit = (e) => {
    e.preventDefault();
    fetch("/api/edit_comment.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id: editId,
        user_id: user.id,
        content: editContent,
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setEditId(null);
          setEditContent("");
          fetch(`/api/comments.php?open_day_id=${id}`)
            .then((res) => res.json())
            .then(setComments);
        }
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
        <div style={{ marginTop: "2rem" }}>
          <h3>Commentaires</h3>
          {user && (
            <form onSubmit={handleAddComment}>
              <textarea
                value={newComment}
                onChange={(e) => setNewComment(e.target.value)}
                placeholder="Votre commentaire"
                required
              />
              <button type="submit">Envoyer</button>
            </form>
          )}
          <ul>
            {comments.length === 0 && <li>Aucun commentaire</li>}
            {comments.map((c) => (
              <li key={c.id}>
                <strong>
                  {c.first_name} {c.last_name}
                </strong>{" "}
                :{" "}
                {editId === c.id ? (
                  <form
                    onSubmit={handleEditSubmit}
                    style={{ display: "inline" }}
                  >
                    <input
                      value={editContent}
                      onChange={(e) => setEditContent(e.target.value)}
                      required
                    />
                    <button type="submit">Valider</button>
                    <button type="button" onClick={() => setEditId(null)}>
                      Annuler
                    </button>
                  </form>
                ) : (
                  <>
                    {c.content}
                    {user && c.user_id === user.id && (
                      <>
                        <button onClick={() => handleEditClick(c)}>
                          Modifier
                        </button>
                        <button onClick={() => handleDeleteComment(c.id)}>
                          Supprimer
                        </button>
                      </>
                    )}
                  </>
                )}
                <span style={{ fontSize: "0.8em", color: "#888" }}>
                  {" "}
                  ({c.moderation_status})
                </span>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  );
}
