import React, { useState } from 'react'
import api from '../services/api.js'

export default function Register(){
  const [form, setForm] = useState({ email:'', first_name:'', last_name:'', password:'' })
  const [msg, setMsg] = useState('')

  function upd(k,v){ setForm(prev=>({...prev, [k]:v})) }

  async function submit(e){
    e.preventDefault()
    setMsg('')
    try{
      await api.post('/api/register', form)
      setMsg('Registered! You can login now.')
    }catch(err){
      setMsg(JSON.stringify(err.response?.data || err.message))
    }
  }

  return (
    <div className="card" style={{marginTop:16}}>
      <h3>Register (User Only)</h3>
      <form onSubmit={submit} className="grid">
        <input placeholder="Email" onChange={e=>upd('email', e.target.value)} />
        <input placeholder="First Name" onChange={e=>upd('first_name', e.target.value)} />
        <input placeholder="Last Name" onChange={e=>upd('last_name', e.target.value)} />
        <input type="password" placeholder="Password" onChange={e=>upd('password', e.target.value)} />
        <button>Register</button>
      </form>
      <h4 style={{marginTop:24}}>Or create User + Teacher in one call</h4>
      <SinglePost />
      {msg && <p>{msg}</p>}
    </div>
  )
}

function SinglePost(){
  const [form, setForm] = useState({
    email:'', first_name:'', last_name:'', password:'',
    university_name:'', gender:'', year_joined:''
  })
  const [msg, setMsg] = useState('')

  function upd(k,v){ setForm(prev=>({...prev, [k]:v})) }

  async function submit(e){
    e.preventDefault()
    setMsg('')
    try{
      const { data } = await api.post('/api/users-with-teacher', { ...form, year_joined: Number(form.year_joined) })
      setMsg('Created: ' + JSON.stringify(data))
    }catch(err){
      setMsg(JSON.stringify(err.response?.data || err.message))
    }
  }

  return (
    <div className="card" style={{marginTop:16}}>
      <h3>Single POST: Create User + Teacher</h3>
      <form onSubmit={submit} className="grid">
        <input placeholder="Email" onChange={e=>upd('email', e.target.value)} />
        <input placeholder="First Name" onChange={e=>upd('first_name', e.target.value)} />
        <input placeholder="Last Name" onChange={e=>upd('last_name', e.target.value)} />
        <input type="password" placeholder="Password" onChange={e=>upd('password', e.target.value)} />
        <input placeholder="University Name" onChange={e=>upd('university_name', e.target.value)} />
        <select onChange={e=>upd('gender', e.target.value)} defaultValue="">
          <option value="" disabled>Gender</option>
          <option>Male</option>
          <option>Female</option>
          <option>Other</option>
        </select>
        <input placeholder="Year Joined" onChange={e=>upd('year_joined', e.target.value)} />
        <button>Create</button>
      </form>
      {msg && <p>{msg}</p>}
    </div>
  )
}
