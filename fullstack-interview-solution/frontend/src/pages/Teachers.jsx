import React, { useEffect, useState } from 'react'
import api from '../services/api.js'

export default function Teachers(){
  const [rows, setRows] = useState([])
  const [err, setErr] = useState('')

  useEffect(()=>{
    api.get('/api/teachers').then(res=> setRows(res.data.data)).catch(e=> setErr('Failed to load'))
  }, [])

  return (
    <div className="card" style={{marginTop:16}}>
      <h3>Teachers</h3>
      {err && <p>{err}</p>}
      <table>
        <thead><tr><th>ID</th><th>Email</th><th>Name</th><th>University</th><th>Gender</th><th>Year Joined</th></tr></thead>
        <tbody>
          {rows.map(r=>(
            <tr key={r.id}>
              <td>{r.id}</td>
              <td>{r.email}</td>
              <td>{r.first_name} {r.last_name}</td>
              <td>{r.university_name}</td>
              <td>{r.gender}</td>
              <td>{r.year_joined}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}
