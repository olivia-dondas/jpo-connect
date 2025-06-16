import { useEffect, useState } from "react";
import "./DirectorDashboard.css";

export default function DirectorDashboard() {
  const [users, setUsers] = useState([]);
  const [registrations, setRegistrations] = useState([]);
  const [jpos, setJpos] = useState([]);
  const [message, setMessage] = useState("");
  const [newJpo, setNewJpo] = useState({
    title: "",
    city: "",
    address: "",
    event_date: "",
    capacity: "",
  });
  const [editJpo, setEditJpo] = useState(null);
  const [inscrits, setInscrits] = useState([]);
  const [selectedJpo, setSelectedJpo] = useState(null);

  // Charger les données au montage
  useEffect(() => {
    fetch("/api/users.php")
      .then((res) => res.json())
      .then(setUsers);
    fetch("/api/registrations.php")
      .then((res) => res.json())
      .then(setRegistrations);
    fetch("/api/open_days.php")
      .then((res) => res.json())
      .then(setJpos);
  }, []);

  // Suppression d'un utilisateur
  const handleDeleteUser = (user_id) => {
    if (!window.confirm("Supprimer cet utilisateur ?")) return;
    fetch(`/api/delete_user.php?id=${user_id}`, { method: "DELETE" })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) setUsers(users.filter((u) => u.id !== user_id));
        setMessage(data.success ? "Utilisateur supprimé" : data.error);
      });
  };

  // Changement de rôle
  const handleChangeRole = (user_id, newRole) => {
    fetch("/api/change_role.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id, role: newRole }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success)
          setUsers(
            users.map((u) => (u.id === user_id ? { ...u, role: newRole } : u))
          );
        setMessage(data.success ? "Rôle modifié" : data.error);
      });
  };

  // CRUD JPO
  const handleJpoChange = (e) => {
    const { name, value } = e.target;
    if (editJpo) {
      setEditJpo({ ...editJpo, [name]: value });
    } else {
      setNewJpo({ ...newJpo, [name]: value });
    }
  };

  const handleAddJpo = (e) => {
    e.preventDefault();
    fetch("/api/create_jpo.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(newJpo),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setJpos([...jpos, data.jpo]);
          setNewJpo({
            title: "",
            city: "",
            address: "",
            event_date: "",
            capacity: "",
          });
          setMessage("JPO ajoutée !");
        } else {
          setMessage(data.error || "Erreur lors de l'ajout");
        }
      });
  };

  const handleEditJpo = (jpo) => setEditJpo(jpo);

  const handleUpdateJpo = (e) => {
    e.preventDefault();
    fetch("/api/update_jpo.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(editJpo),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setJpos(jpos.map((j) => (j.id === editJpo.id ? editJpo : j)));
          setEditJpo(null);
          setMessage("JPO modifiée !");
        } else {
          setMessage(data.error || "Erreur lors de la modification");
        }
      });
  };

  const handleDeleteJpo = (id) => {
    if (!window.confirm("Supprimer cette JPO ?")) return;
    fetch(`/api/delete_jpo.php?id=${id}`, { method: "DELETE" })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) setJpos(jpos.filter((j) => j.id !== id));
        setMessage(data.success ? "JPO supprimée" : data.error);
      });
  };

  const handleShowInscrits = (jpo_id) => {
    fetch(`/api/jpo_registrations.php?jpo_id=${jpo_id}`)
      .then((res) => res.json())
      .then((data) => {
        setInscrits(data);
        setSelectedJpo(jpo_id);
      });
  };

  return (
    <div className="director-dashboard">
      <h2>Dashboard Directeur</h2>
      {message && <div className="dashboard-message">{message}</div>}

      <section>
        <h3>Utilisateurs</h3>
        <table>
          <thead>
            <tr>
              <th>Nom</th>
              <th>Email</th>
              <th>Rôle</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {users.map((u) => (
              <tr key={u.id}>
                <td>
                  {u.first_name} {u.last_name}
                </td>
                <td>{u.email}</td>
                <td>
                  <select
                    value={u.role}
                    onChange={(e) => handleChangeRole(u.id, e.target.value)}
                  >
                    <option value="student">Student</option>
                    <option value="employee">Employee</option>
                    <option value="director">Director</option>
                    <option value="admin">Admin</option>
                  </select>
                </td>
                <td>
                  <button onClick={() => handleDeleteUser(u.id)}>
                    Supprimer
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </section>

      <section>
        <h3>Inscriptions</h3>
        <ul>
          {registrations.map((r) => (
            <li key={r.id}>
              {r.user_first_name} {r.user_last_name} inscrit à {r.jpo_title}
            </li>
          ))}
        </ul>
      </section>

      <section>
        <h3>Gestion des JPO</h3>
        <form
          onSubmit={editJpo ? handleUpdateJpo : handleAddJpo}
          className="jpo-form"
        >
          <input
            name="title"
            placeholder="Titre"
            value={editJpo ? editJpo.title : newJpo.title}
            onChange={handleJpoChange}
            required
          />
          <input
            name="city"
            placeholder="Ville"
            value={editJpo ? editJpo.city : newJpo.city}
            onChange={handleJpoChange}
            required
          />
          <input
            name="address"
            placeholder="Adresse"
            value={editJpo ? editJpo.address : newJpo.address}
            onChange={handleJpoChange}
            required
          />
          <input
            name="event_date"
            type="datetime-local"
            placeholder="Date"
            value={editJpo ? editJpo.event_date : newJpo.event_date}
            onChange={handleJpoChange}
            required
          />
          <input
            name="capacity"
            type="number"
            placeholder="Capacité"
            value={editJpo ? editJpo.capacity : newJpo.capacity}
            onChange={handleJpoChange}
            required
          />
          <button type="submit">{editJpo ? "Modifier" : "Ajouter"} JPO</button>
          {editJpo && (
            <button type="button" onClick={() => setEditJpo(null)}>
              Annuler
            </button>
          )}
        </form>
        <table>
          <thead>
            <tr>
              <th>Titre</th>
              <th>Ville</th>
              <th>Date</th>
              <th>Capacité</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {jpos.map((jpo) => (
              <tr key={jpo.id}>
                <td>{jpo.title}</td>
                <td>{jpo.city}</td>
                <td>{new Date(jpo.event_date).toLocaleString()}</td>
                <td>{jpo.capacity}</td>
                <td>
                  <button onClick={() => handleEditJpo(jpo)}>Modifier</button>
                  <button onClick={() => handleDeleteJpo(jpo.id)}>
                    Supprimer
                  </button>
                  <button onClick={() => handleShowInscrits(jpo.id)}>
                    Voir inscrits
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        {selectedJpo && (
          <div>
            <h4>Inscrits à la JPO {selectedJpo}</h4>
            <ul>
              {inscrits.length === 0 && <li>Aucun inscrit</li>}
              {inscrits.map((u, i) => (
                <li key={i}>
                  {u.first_name} {u.last_name} ({u.email})
                </li>
              ))}
            </ul>
            <button onClick={() => setSelectedJpo(null)}>Fermer</button>
          </div>
        )}
      </section>
    </div>
  );
}
