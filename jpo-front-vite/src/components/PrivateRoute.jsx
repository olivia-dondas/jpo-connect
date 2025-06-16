import React from "react";
import { Navigate } from "react-router-dom";

export default function PrivateRoute({ children, allowedRoles }) {
  const user = JSON.parse(localStorage.getItem("user"));
  const role = user?.role;

  if (!user || (allowedRoles && !allowedRoles.includes(role))) {
    return <Navigate to="/" />;
  }

  return children;
}
