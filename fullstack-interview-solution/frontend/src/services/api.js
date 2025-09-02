import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE || 'http://localhost:8080',
})

export function setToken(token){
  localStorage.setItem('token', token)
}
export function getToken(){
  return localStorage.getItem('token')
}
export function clearToken(){
  localStorage.removeItem('token')
}

api.interceptors.request.use((config)=>{
  const t = getToken()
  if (t) config.headers.Authorization = `Bearer ${t}`
  return config
})

export default api
