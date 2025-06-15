import { Navigate } from "react-router-dom";

// Simule l'utilisateur connecté et son rôle (à remplacer par ta vraie logique)
const fakeAuth = {
  isAuthenticated: () => !!localStorage.getItem("user"),
  role: () => JSON.parse(localStorage.getItem("user") || "{}").role,
};

export default function PrivateRoute({ children, roles }) {
  if (!fakeAuth.isAuthenticated()) {
    return <Navigate to="/login" />;
  }
  if (roles && !roles.includes(fakeAuth.role())) {
    return <Navigate to="/" />;
  }
  return children;
}