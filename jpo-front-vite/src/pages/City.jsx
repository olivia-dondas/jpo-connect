import React, { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";

export default function City() {
  const { city } = useParams();
  const [jpos, setJpos] = useState([]);

  useEffect(() => {
    fetch(`http://localhost:8000/api/jpos?city=${city}`)
      .then(res => res.json())
      .then(data => setJpos(data));
  }, [city]);

  return (
    <div>
      <h2>JPO à {city.charAt(0).toUpperCase() + city.slice(1)}</h2>
      <ul>
        {jpos.map(jpo => (
          <li key={jpo.id}>
            <Link to={`/jpo/${jpo.id}`}>
              {jpo.title} – {new Date(jpo.event_date).toLocaleDateString()}
            </Link>
          </li>
        ))}
      </ul>
    </div>
  );
}