import React, { useEffect, useState } from 'react'
import api from '../services/api.js'

export default function Me(){
  const [user, setUser] = useState(null)
  const [msg, setMsg] = useState('')

  useEffect(()=>{
    api.get('/api/me').then(res=> setUser(res.data.user)).catch(e=> setMsg('Not logged in'))
  }, [])

  return (
    <div className="card" style={{marginTop:16}}>
      <h3>Profile</h3>
      {user ? <pre>{JSON.stringify(user,null,2)}</pre> : <p>{msg}</p>}
    </div>
  )
}
