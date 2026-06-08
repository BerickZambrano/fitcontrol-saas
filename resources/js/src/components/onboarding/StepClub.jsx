import { useEffect, useState } from 'react'

export default function StepClub({ data, onChange, onNext, errors }) {
  const [cities, setCities] = useState([])
  const [localError, setLocalError] = useState("")

  useEffect(() => {
    fetch('/colombia.json')
      .then(res => res.json())
      .then(json => {
        const allCities = json.reduce((acc, current) => {
          return acc.concat(current.ciudades)
        }, [])
        const uniqueCities = [...new Set(allCities)].sort((a, b) => a.localeCompare(b))
        setCities(uniqueCities)
      })
      .catch(err => console.error('Error al cargar ciudades de Colombia:', err))
  }, [])

  const normalizeStr = (str) => 
    str ? str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim() : "";

  const handleSubmit = (e) => {
    e.preventDefault()

    if (cities.length > 0) {
      const matchedCity = cities.find(
        (city) => normalizeStr(city) === normalizeStr(data.ciudad)
      )

      if (!matchedCity) {
        setLocalError("Debes elegir una ciudad válida de Colombia de la lista.")
        return
      } else {
        // Auto-correct to official spelling
        onChange({ ciudad: matchedCity })
      }
    }

    setLocalError("")
    onNext()
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-bold text-gray-900 leading-tight">Datos del club</h2>
        <p className="text-gray-500 text-sm">Cuéntanos sobre tu organización deportiva.</p>
      </div>

      <div className="space-y-4">
        <div className="space-y-1.5">
          <label className="block text-xs font-black uppercase tracking-widest text-gray-400">Nombre del club</label>
          <input
            type="text" required
            value={data.nombre}
            onChange={e => onChange({ nombre: e.target.value })}
            placeholder="Ej: Club Deportivo Titanes"
            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder:text-gray-300"
          />
          {errors.nombre && <p className="text-red-500 text-xs font-medium mt-1">{errors.nombre}</p>}
        </div>

        <div className="space-y-1.5">
          <label className="block text-xs font-black uppercase tracking-widest text-gray-400">Nombre corto (Siglas)</label>
          <input
            type="text" required
            value={data.nombre_corto}
            onChange={e => onChange({ nombre_corto: e.target.value })}
            placeholder="Ej: CDT"
            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder:text-gray-300"
          />
          {errors.nombre_corto && <p className="text-red-500 text-xs font-medium mt-1">{errors.nombre_corto}</p>}
        </div>

        <div className="space-y-1.5">
          <label className="block text-xs font-black uppercase tracking-widest text-gray-400">Tipo de club</label>
          <select
            required
            value={data.tipo_club}
            onChange={e => onChange({ tipo_club: e.target.value })}
            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all text-gray-700 appearance-none"
          >
            <option value="" disabled>Selecciona el tipo de club...</option>
            <option value="formativo">⚽ Formativo — Escuela o academia deportiva</option>
            <option value="amateur">🏆 Amateur — Liga o torneo no profesional</option>
            <option value="profesional">⭐ Profesional — Club de alto rendimiento</option>
          </select>
          {errors.tipo_club && <p className="text-red-500 text-xs font-medium mt-1">{errors.tipo_club}</p>}
        </div>

        <div className="grid grid-cols-2 gap-4">
          <div className="space-y-1.5">
            <label className="block text-xs font-black uppercase tracking-widest text-gray-400">Ciudad</label>
            <input
              type="text" required
              list="colombia-cities"
              value={data.ciudad}
              onChange={e => {
                onChange({ ciudad: e.target.value })
                if (localError) setLocalError("")
              }}
              placeholder="Bogotá"
              className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder:text-gray-300"
            />
            <datalist id="colombia-cities">
              {cities.map(city => (
                <option key={city} value={city} />
              ))}
            </datalist>
            {errors.ciudad && <p className="text-red-500 text-xs font-medium mt-1">{errors.ciudad}</p>}
            {localError && <p className="text-red-500 text-xs font-medium mt-1">{localError}</p>}
          </div>

          <div className="space-y-1.5">
            <label className="block text-xs font-black uppercase tracking-widest text-gray-400">País</label>
            <input
              type="text" required
              value={data.pais}
              onChange={e => onChange({ pais: e.target.value })}
              placeholder="Colombia"
              className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder:text-gray-300"
            />
            {errors.pais && <p className="text-red-500 text-xs font-medium mt-1">{errors.pais}</p>}
          </div>
        </div>

        <div className="space-y-1.5">
          <label className="block text-xs font-black uppercase tracking-widest text-gray-400">Dirección</label>
          <input
            type="text" required
            value={data.direccion}
            onChange={e => onChange({ direccion: e.target.value })}
            placeholder="Calle 123 # 45-67"
            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder:text-gray-300"
          />
          {errors.direccion && <p className="text-red-500 text-xs font-medium mt-1">{errors.direccion}</p>}
        </div>

        <div className="space-y-1.5">
          <label className="block text-xs font-black uppercase tracking-widest text-gray-400">Teléfono Corporativo</label>
          <input
            type="tel" required
            value={data.telefono}
            onChange={e => onChange({ telefono: e.target.value })}
            placeholder="+57 300 000 0000"
            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder:text-gray-300"
          />
          {errors.telefono && <p className="text-red-500 text-xs font-medium mt-1">{errors.telefono}</p>}
        </div>

        <div className="space-y-1.5">
          <label className="block text-xs font-black uppercase tracking-widest text-gray-400">
            Escudo del club <span className="text-gray-300 font-normal italic">(opcional)</span>
          </label>
          <div
            className={`group border-2 border-dashed rounded-2xl p-6 text-center cursor-pointer transition-all duration-300
              ${data.escudo_url ? 'border-blue-300 bg-blue-50/50' : 'border-gray-200 hover:border-blue-400 hover:bg-gray-50'}`}
            onClick={() => document.getElementById('escudo-input').click()}>
            {data.escudo_url ? (
              <div className="flex flex-col items-center gap-2 text-blue-600">
                <div className="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                  <span>✓</span>
                </div>
                <span className="text-sm font-bold truncate max-w-[200px]">{data.escudo_url.name}</span>
              </div>
            ) : (
              <div className="space-y-1">
                <div className="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mx-auto group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                  <span className="text-xl">+</span>
                </div>
                <p className="text-gray-500 text-sm font-medium">Subir escudo</p>
                <p className="text-gray-300 text-xs">PNG o JPG, máx 2MB</p>
              </div>
            )}
            <input
              id="escudo-input" type="file"
              accept="image/png,image/jpeg"
              className="hidden"
              onChange={e => onChange({ escudo_url: e.target.files[0] })}
            />
          </div>
          {errors.escudo_url && <p className="text-red-500 text-xs font-medium mt-1">{errors.escudo_url}</p>}
        </div>
      </div>

      <button type="submit"
        className="w-full bg-blue-600 text-white py-4 rounded-xl font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/25 active:scale-[0.98]">
        Siguiente paso
      </button>
    </form>
  )
}