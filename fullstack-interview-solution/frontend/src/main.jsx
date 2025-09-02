import React from 'react'
import { createRoot } from 'react-dom/client'
import { BrowserRouter, Routes, Route, Link, Navigate } from 'react-router-dom'
import Login from './pages/Login.jsx'
import Register from './pages/Register.jsx'
import Me from './pages/Me.jsx'
import Users from './pages/Users.jsx'
import Teachers from './pages/Teachers.jsx'
import { getToken } from './services/api.js'

function Layout({ children }){
  return (
    <div>
      <nav className="nav">
        <Link to="/">Home</Link>
        <Link to="/register">Register</Link>
        <Link to="/login">Login</Link>
        <Link to="/me">Profile</Link>
        <Link to="/users">Users</Link>
        <Link to="/teachers">Teachers</Link>
      </nav>
      <div className="container">
        <div className="hero card">
          <h2>CI4 + React Auth Demo</h2>
          <p>JWT-protected APIs with single POST for user + teacher</p>
        </div>
        {children}
      </div>
    </div>
  )
}

function Protected({ children }){
  if(!getToken()) return <Navigate to="/login" replace />
  return children
}

function App(){
  return (
    <BrowserRouter>
      <Layout>
        <Routes>
          <Route path="/" element={<div className="card"><p>Use the nav links.</p></div>} />
          <Route path="/register" element={<Register />} />
          <Route path="/login" element={<Login />} />
          <Route path="/me" element={<Protected><Me /></Protected>} />
          <Route path="/users" element={<Protected><Users /></Protected>} />
          <Route path="/teachers" element={<Protected><Teachers /></Protected>} />
        </Routes>
      </Layout>
    </BrowserRouter>
  )
}

createRoot(document.getElementById('root')).render(<App />)
