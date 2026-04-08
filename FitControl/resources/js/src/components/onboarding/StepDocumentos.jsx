import React from 'react'

function FileField({ id, label, hint, accept, value, onChange, error }) {
  return (
    <div className="space-y-1.5">
      <label className="block text-xs font-black uppercase tracking-widest text-gray-400">{label}</label>

      <div
        className={`group border-2 border-dashed rounded-2xl p-6 text-center cursor-pointer transition-all duration-300
        ${value ? 'border-green-300 bg-green-50/50' : 'border-gray-200 hover:border-blue-400 hover:bg-gray-50'}`}
        onClick={() => document.getElementById(id).click()}
      >
        {value ? (
          <div className="flex flex-col items-center gap-2 text-green-600">
            <div className="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg">
              ✓
            </div>
            <span className="text-sm font-bold truncate max-w-[250px]">{value.name}</span>
          </div>
        ) : (
          <div className="space-y-1">
            <div className="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mx-auto group-hover:bg-blue-600 group-hover:text-white transition-all">
              +
            </div>
            <p className="text-gray-500 text-sm font-medium">Subir archivo</p>
            <p className="text-gray-300 text-xs italic">{hint}</p>
          </div>
        )}

        <input
          id={id}
          type="file"
          accept={accept}
          className="hidden"
          onChange={onChange}
        />
      </div>

      {error && <p className="text-red-500 text-xs font-medium mt-1">{error}</p>}
    </div>
  )
}

export default function StepDocumentos({ data, onChange, onNext, onBack, errors }) {

  const handleSubmit = (e) => {
    e.preventDefault()

    // DEBUG 🔥 (te recomiendo dejarlo mientras pruebas)
    console.log('DATA ENVIADA:', data)

    onNext()
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-6">

      {/* TITULO */}
      <div>
        <h2 className="text-2xl font-bold text-gray-900">Documentos legales</h2>
        <p className="text-gray-500 text-sm">Necesitamos validar la información jurídica de tu club.</p>
      </div>

      {/* FORM */}
      <div className="space-y-4">

        {/* NIT */}
        <div>
          <label className="block text-xs font-black uppercase tracking-widest text-gray-400">
            NIT del club
          </label>
          <input
            type="text"
            required
            value={data.nit}
            onChange={e => onChange({ nit: e.target.value })}
            placeholder="Ej: 900123456-1"
            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
          />
          {errors.nit && <p className="text-red-500 text-xs">{errors.nit}</p>}
        </div>

        {/* EMAIL CORPORATIVO 🔥 NUEVO */}
        <div>
          <label className="block text-xs font-black uppercase tracking-widest text-gray-400">
            Email corporativo del club
          </label>
          <input
            type="email"
            required
            value={data.email_corporativo}
            onChange={e => onChange({ email_corporativo: e.target.value })}
            placeholder="club@empresa.com"
            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
          />
          {errors.email_corporativo && (
            <p className="text-red-500 text-xs">{errors.email_corporativo}</p>
          )}
        </div>

        {/* ENCARGADO */}
        <div>
          <label className="block text-xs font-black uppercase tracking-widest text-gray-400">
            Nombre del encargado legal
          </label>
          <input
            type="text"
            required
            value={data.encargado_nombre}
            onChange={e => onChange({ encargado_nombre: e.target.value })}
            placeholder="Juan Pérez"
            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
          />
          {errors.encargado_nombre && (
            <p className="text-red-500 text-xs">{errors.encargado_nombre}</p>
          )}
        </div>

        {/* EMAIL + TEL */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">

          {/* EMAIL ENCARGADO */}
          <div>
            <label className="block text-xs font-black uppercase tracking-widest text-gray-400">
              Email del encargado
            </label>
            <input
              type="email"
              required
              value={data.encargado_email}
              onChange={e => onChange({ encargado_email: e.target.value })}
              placeholder="encargado@email.com"
              className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
            />
            {errors.encargado_email && (
              <p className="text-red-500 text-xs">{errors.encargado_email}</p>
            )}
          </div>

          {/* TEL */}
          <div>
            <label className="block text-xs font-black uppercase tracking-widest text-gray-400">
              Teléfono del encargado
            </label>
            <input
              type="tel"
              required
              value={data.encargado_telefono}
              onChange={e => onChange({ encargado_telefono: e.target.value })}
              placeholder="+57 300..."
              className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
            />
            {errors.encargado_telefono && (
              <p className="text-red-500 text-xs">{errors.encargado_telefono}</p>
            )}
          </div>
        </div>

        {/* ARCHIVOS */}
        <FileField
          id="rut-input"
          label="Documento RUT"
          hint="PDF, JPG o PNG"
          accept="application/pdf,image/jpeg,image/png"
          value={data.rut_document}
          onChange={e => onChange({ rut_document: e.target.files[0] })}
          error={errors.rut_document}
        />

        <FileField
          id="camara-input"
          label="Cámara de comercio"
          hint="PDF, JPG o PNG"
          accept="application/pdf,image/jpeg,image/png"
          value={data.camara_comercio}
          onChange={e => onChange({ camara_comercio: e.target.files[0] })}
          error={errors.camara_comercio}
        />

      </div>

      {/* BOTONES */}
      <div className="flex gap-4">
        <button
          type="button"
          onClick={onBack}
          className="flex-1 bg-gray-100 py-3 rounded-xl font-bold"
        >
          Atrás
        </button>

        <button
          type="submit"
          className="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold"
        >
          Siguiente
        </button>
      </div>

    </form>
  )
}