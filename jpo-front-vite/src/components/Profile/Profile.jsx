import React, { useEffect, useState } from "react";
import "./Profile.css";

export default function Profile() {
  const [user, setUser] = useState(null);
  const [jpos, setJpos] = useState([]);
  const [error, setError] = useState("");
  const [edit, setEdit] = useState(false);
  const [form, setForm] = useState({
    first_name: "",
    last_name: "",
    phone_number: "",
  });

  useEffect(() => {
    const stored = JSON.parse(localStorage.getItem("user"));
    if (!stored) {
      setError("Utilisateur non connecté");
      return;
    }
    const email = encodeURIComponent(stored.email);
    fetch(`/api/user_profile.php?email=${email}`)
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setUser(data.user);
          setJpos(data.jpos);
          setForm({
            first_name: data.user.first_name || "",
            last_name: data.user.last_name || "",
            phone_number: data.user.phone_number || "",
          });
        } else {
          setError(data.message);
        }
      })
      .catch(() => setError("Erreur serveur"));
  }, []);

  const handleChange = (e) =>
    setForm({ ...form, [e.target.name]: e.target.value });

  const handleSave = async (e) => {
    e.preventDefault();
    setError("");
    const res = await fetch("/api/update_profile.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ...form, email: user.email }),
    });
    const data = await res.json();
    if (data.success) {
      setUser({ ...user, ...form });
      setEdit(false);
    } else {
      setError(data.message || "Erreur lors de la mise à jour");
    }
  };

  if (error) return <div className="profile-error">{error}</div>;
  if (!user) return <div>Chargement...</div>;

  return (
    <div className="profile-container">
      <h2>Mon profil</h2>
      {edit ? (
        <form onSubmit={handleSave} className="profile-form">
          <input
            name="first_name"
            value={form.first_name}
            onChange={handleChange}
            placeholder="Prénom"
          />
          <input
            name="last_name"
            value={form.last_name}
            onChange={handleChange}
            placeholder="Nom"
          />
          <input
            name="phone_number"
            value={form.phone_number}
            onChange={handleChange}
            placeholder="Téléphone"
          />
          <button type="submit">Enregistrer</button>
          <button type="button" onClick={() => setEdit(false)}>
            Annuler
          </button>
        </form>
      ) : (
        <>
          <div>
            <b>Nom :</b> {user.first_name} {user.last_name}
          </div>
          <div>
            <b>Email :</b> {user.email}
          </div>
          <div>
            <b>Téléphone :</b> {user.phone_number}
          </div>
          <div>
            <b>Rôle :</b> {user.role}
          </div>
          <button onClick={() => setEdit(true)}>Modifier mon profil</button>
        </>
      )}
      <h3>Mes JPO</h3>
      {jpos.length === 0 ? (
        <div>Aucune inscription à une JPO.</div>
      ) : (
        <ul>
          {jpos.map((jpo) => (
            <li key={jpo.id}>
              {jpo.title} ({jpo.event_date})
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
