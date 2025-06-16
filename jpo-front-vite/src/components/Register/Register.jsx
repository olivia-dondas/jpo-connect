import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "./Register.css";

export default function Register() {
  const [form, setForm] = useState({
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    confirm_password: "",
  });
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");
  const navigate = useNavigate();

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setSuccess("");
    if (form.password !== form.confirm_password) {
      setError("Les mots de passe ne correspondent pas.");
      return;
    }
    try {
      const res = await fetch("/api/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          first_name: form.first_name,
          last_name: form.last_name,
          email: form.email,
          password: form.password,
        }),
      });

      const text = await res.text();
      console.log("Réponse brute:", text);
      const data = JSON.parse(text);

      if (data.success) {
        setSuccess("Inscription réussie ! Vous pouvez vous connecter.");
        setTimeout(() => navigate("/login"), 1500);
      } else {
        setError(data.error || "Erreur lors de l'inscription.");
      }
    } catch (err) {
      setError("Erreur de connexion au serveur.");
    }
  };

  return (
    <div className="register-container">
      <h2>Inscription</h2>
      <form onSubmit={handleSubmit}>
        <input
          name="first_name"
          placeholder="Prénom"
          value={form.first_name}
          onChange={handleChange}
          required
          autoComplete="given-name"
        />
        <input
          name="last_name"
          placeholder="Nom"
          value={form.last_name}
          onChange={handleChange}
          required
          autoComplete="family-name"
        />
        <input
          name="email"
          type="email"
          placeholder="Email"
          value={form.email}
          onChange={handleChange}
          required
          autoComplete="username"
        />
        <input
          name="password"
          type="password"
          placeholder="Mot de passe"
          value={form.password}
          onChange={handleChange}
          required
          autoComplete="new-password"
        />
        <input
          name="confirm_password"
          type="password"
          placeholder="Confirmer le mot de passe"
          value={form.confirm_password}
          onChange={handleChange}
          required
          autoComplete="new-password"
        />
        <button type="submit">S'inscrire</button>
        {error && <div className="register-error">{error}</div>}
        {success && <div className="register-success">{success}</div>}
      </form>
    </div>
  );
}
