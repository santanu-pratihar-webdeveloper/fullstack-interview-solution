import React, { useEffect, useState } from 'react'
import api from '../services/api.js'

export default function Users(){
  const [rows, setRows] = useState([])
  const [err, setErr] = useState('')

  useEffect(()=>{
    api.get('/api/auth-users').then(res=> setRows(res.data.data)).catch(e=> setErr('Failed to load'))
  }, [])

  return (
    <div className="card" style={{marginTop:16}}>
      <h3>Auth Users</h3>
      {err && <p>{err}</p>}
      <table>
        <thead><tr><th>ID</th><th>Email</th><th>Name</th><th>Created</th></tr></thead>
        <tbody>
          {rows.map(r=>(
            <tr key={r.id}>
              <td>{r.id}</td>
              <td>{r.email}</td>
              <td>{r.first_name} {r.last_name}</td>
              <td>{r.created_at}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}
