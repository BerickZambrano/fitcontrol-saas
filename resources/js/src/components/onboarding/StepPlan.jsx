const PLANES = [
  {
    id: 'mensual',
    nombre: 'Mensual',
    precio: '$70.000',
    periodo: 'por mes',
    badge: null,
    features: ['Jugadores ilimitados', 'Reportes básicos', 'Soporte por email'],
  },
  {
    id: 'anual',
    nombre: 'Anual',
    precio: '$700.000',
    periodo: 'por año',
    badge: '2 meses gratis',
    features: ['Todo lo del mensual', 'Reportes avanzados', 'Soporte prioritario'],
  },
]

export default function StepPlan({ data, onChange, onBack, onSubmit, loading, errors }) {
  const handleSubmit = (e) => {
    e.preventDefault()
    onSubmit()
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-bold text-gray-900 leading-tight">Elige tu plan</h2>
        <p className="text-gray-500 text-sm">Selecciona la opción que mejor se adapte a tu club.</p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {PLANES.map(plan => (
          <div
            key={plan.id}
            onClick={() => onChange({ plan: plan.id })}
            className={`relative border-2 rounded-2xl p-5 cursor-pointer transition-all duration-300 group
              ${data.plan === plan.id ? 'border-blue-600 bg-blue-50/50 shadow-lg shadow-blue-500/10' : 'border-gray-100 hover:border-blue-200 hover:bg-gray-50'}`}>

            {plan.badge && (
              <span className="absolute -top-3 left-1/2 -translate-x-1/2 bg-green-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-sm z-10">
                {plan.badge}
              </span>
            )}

            <div className="space-y-1 mb-4">
              <p className={`font-black uppercase tracking-widest text-xs ${data.plan === plan.id ? 'text-blue-600' : 'text-gray-400'}`}>
                {plan.nombre}
              </p>
              <div className="flex items-baseline gap-1">
                <span className="text-2xl font-black text-gray-900">{plan.precio}</span>
                <span className="text-xs text-gray-400 font-medium">{plan.periodo}</span>
              </div>
            </div>

            <ul className="space-y-2 mb-4">
              {plan.features.map((f, i) => (
                <li key={i} className="text-[11px] text-gray-600 flex items-center gap-2">
                  <div className="w-4 h-4 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span className="text-blue-600 text-[10px]">✓</span>
                  </div>
                  <span className="font-medium">{f}</span>
                </li>
              ))}
            </ul>

            <div className={`mt-auto w-full h-1 rounded-full transition-all duration-300 ${data.plan === plan.id ? 'bg-blue-600 shadow-lg shadow-blue-500/50' : 'bg-gray-100'}`} />
          </div>
        ))}
      </div>

      {errors.plan && <p className="text-red-500 text-xs font-medium mt-1">{errors.plan}</p>}

      {data.plan && (
        <div className="bg-gray-900 rounded-2xl p-6 text-sm text-gray-300 space-y-3 relative overflow-hidden">
          <div className="absolute top-0 right-0 p-4 opacity-10">
            <span className="text-4xl font-black italic">FC</span>
          </div>
          
          <p className="font-black uppercase tracking-widest text-xs text-blue-500">Resumen de solicitud</p>
          
          <div className="grid grid-cols-2 gap-y-2 text-xs">
            <span className="text-gray-500">Club:</span>
            <span className="font-bold text-white truncate">{data.nombre}</span>
            
            <span className="text-gray-500">Encargado:</span>
            <span className="font-bold text-white truncate">{data.encargado_nombre}</span>
            
            <span className="text-gray-500">Plan:</span>
            <span className="font-bold text-blue-400">
              {data.plan === 'mensual' ? 'Mensual — $70.000/mes' : 'Anual — $700.000/año'}
            </span>
          </div>
        </div>
      )}

      <p className="text-[10px] text-gray-400 text-center px-4 leading-relaxed uppercase tracking-wider font-bold">
        Al hacer clic en "Enviar solicitud" aceptas nuestros{' '}
        <a href="/terminos" className="text-blue-600 hover:underline">términos y condiciones</a>
      </p>

      <div className="flex gap-4">
        <button type="button" onClick={onBack}
          className="flex-1 bg-gray-50 text-gray-500 py-4 rounded-xl font-black uppercase tracking-widest hover:bg-gray-100 hover:text-gray-700 transition-all active:scale-[0.98]">
          Atrás
        </button>
        <button
          type="submit"
          disabled={!data.plan || loading}
          className="flex-1 bg-blue-600 text-white py-4 rounded-xl font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/25 disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.98]">
          {loading ? 'Procesando...' : 'Enviar solicitud'}
        </button>
      </div>
    </form>
  )
}