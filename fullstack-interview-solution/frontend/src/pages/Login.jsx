import React, { useState } from 'react'
import api, { setToken } from '../services/api.js'

export default function Login(){
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [msg, setMsg] = useState('')

  async function submit(e){
    e.preventDefault()
    setMsg('')
    try{
      const { data } = await api.post('/api/login', { email, password })
      setToken(data.token)
      setMsg('Logged in!')
    }catch(err){
      setMsg(err.response?.data?.error || 'Login failed')
    }
  }

  return (
    <div className="card" style={{marginTop:16}}>
      <h3>Login</h3>
      <form onSubmit={submit} className="grid">
        <input placeholder="Email" value={email} onChange={e=>setEmail(e.target.value)} />
        <input type="password" placeholder="Password" value={password} onChange={e=>setPassword(e.target.value)} />
        <button>Login</button>
      </form>
      {msg && <p>{msg}</p>}
    </div>
  )
}
