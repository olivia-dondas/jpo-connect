import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import "./Login.css";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    console.log("Tentative de connexion...");
    try {
      const res = await fetch("http://localhost:8000/api/login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
      });
      const data = await res.json();
      if (data.success) {
        // Stocke l'utilisateur et le token
        localStorage.setItem("user", JSON.stringify(data.user));
        localStorage.setItem("token", data.token);
        navigate("/profile");
      } else {
        setError(data.message || "Identifiants invalides");
      }
    } catch {
      setError("Erreur de connexion au serveur");
    }
  };

  return (
    <div className="login-container">
      <h2>Connexion</h2>
      <form onSubmit={handleSubmit}>
        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
          autoComplete="username"
        />
        <input
          type="password"
          placeholder="Mot de passe"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
          autoComplete="current-password"
        />
        <button type="submit">Se connecter</button>
        {error && <div className="login-error">{error}</div>}
      </form>
    </div>
  );
}
