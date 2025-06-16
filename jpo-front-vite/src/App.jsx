import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import Home from "./pages/Home/Home";
import JpoDetails from "./components/JpoDetails/JpoDetails";
import Login from "./components/Login/Login";
import Register from "./components/Register/Register";
import Profile from "./components/Profile/Profile";
import AdminDashboard from "./pages/AdminDashboard";
import Moderation from "./pages/Moderation";
import PrivateRoute from "./components/PrivateRoute";
import Header from "./components/Header/Header";
import Banner from "./components/Banner/Banner";
import Footer from "./components/Footer/Footer";
import City from "./components/City/City";
import "./App.css";
import DirectorDashboard from "./components/DirectorDashboard/DirectorDashboard";

function App() {
  return (
    <Router>
      <Header />
      <Banner />
      <div className="app-content">
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/jpo/:id" element={<JpoDetails />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route
            path="/profile"
            element={
              <PrivateRoute
                roles={["student", "employee", "manager", "director"]}
              >
                <Profile />
              </PrivateRoute>
            }
          />
          <Route
            path="/admin"
            element={
              <PrivateRoute roles={["director"]}>
                <AdminDashboard />
              </PrivateRoute>
            }
          />
          <Route
            path="/moderation"
            element={
              <PrivateRoute roles={["manager", "director"]}>
                <Moderation />
              </PrivateRoute>
            }
          />
          <Route path="/city/:city" element={<City />} />
          <Route path="/director-dashboard" element={<DirectorDashboard />} />
        </Routes>
      </div>
      <Footer />
    </Router>
  );
}

export default App;
