import React, { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";

export default function City() {
  const { city } = useParams();
  const [jpos, setJpos] = useState([]);

  useEffect(() => {
    // 1. Récupérer l'id du lieu
    fetch(`http://localhost:8000/api/location.php?city=${city}`)
      .then((res) => res.json())
      .then((locations) => {
        if (locations.length > 0) {
          const locationId = locations[0].id;
          // 2. Récupérer les JPO pour cet id
          fetch(`http://localhost:8000/api/jpos.php?location_id=${locationId}`)
            .then((res) => res.json())
            .then((data) => setJpos(Array.isArray(data) ? data : []));
        } else {
          setJpos([]);
        }
      });
  }, [city]);

  return (
    <div>
      <h2>JPO à {city.charAt(0).toUpperCase() + city.slice(1)}</h2>
      <ul>
        {jpos.length === 0 && <li>Aucune JPO prévue pour cette ville.</li>}
        {jpos.map((jpo) => (
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
