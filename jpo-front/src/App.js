import React, { useEffect, useState } from "react";
import "./App.css";

function App() {
  const [jpos, setJpos] = useState([]);
  const [selectedJpo, setSelectedJpo] = useState(null);

  useEffect(() => {
    fetch("http://localhost:8000/api/jpos")
      .then((res) => res.json())
      .then((data) => setJpos(data));
  }, []);

  return (
    <div className="App">
      <h1>Liste des JPO</h1>
      <ul>
        {jpos.map((jpo) => (
          <li key={jpo.id}>
            <button
              onClick={() => setSelectedJpo(jpo)}
              style={{
                background: "none",
                border: "none",
                color: "#61dafb",
                cursor: "pointer",
              }}
            >
              <strong>{jpo.title}</strong> — {jpo.event_date}
            </button>
          </li>
        ))}
      </ul>
      {selectedJpo && (
        <div
          style={{
            marginTop: 20,
            background: "#222",
            padding: 20,
            borderRadius: 8,
          }}
        >
          <h2>Détail JPO</h2>
          <p>
            <strong>Titre :</strong> {selectedJpo.title}
          </p>
          <p>
            <strong>Date :</strong> {selectedJpo.event_date}
          </p>
          <p>
            <strong>Description :</strong> {selectedJpo.description}
          </p>
          <p>
            <strong>Capacité :</strong> {selectedJpo.capacity}
          </p>
          <p>
            <strong>Status :</strong> {selectedJpo.status}
          </p>
          <button onClick={() => setSelectedJpo(null)}>Fermer</button>
        </div>
      )}
    </div>
  );
}

export default App;
