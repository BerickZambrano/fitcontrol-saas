import { Link } from '@inertiajs/react'

export default function Success() {
  return (
    <div className="min-h-screen bg-white font-['Outfit',_sans-serif] flex items-center justify-center px-6 overflow-hidden relative">
      {/* Background elements */}
      <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 via-blue-400 to-blue-600" />
      <div className="absolute top-[-10%] right-[-10%] w-[400px] h-[400px] bg-blue-50 rounded-full blur-3xl opacity-50" />
      <div className="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-blue-50 rounded-full blur-3xl opacity-50" />

      <div className="max-w-md w-full relative z-10 text-center">
        <div className="mb-10 inline-flex items-center gap-2">
          <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
            <span className="text-white font-bold text-lg italic">FC</span>
          </div>
          <span className="text-gray-900 text-2xl font-black tracking-tighter uppercase italic">FitControl</span>
        </div>

        <div className="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl shadow-blue-500/5 p-12 mb-8">
          <div className="w-20 h-20 bg-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-xl shadow-blue-500/30 transform -rotate-6">
            <span className="text-white text-4xl">✓</span>
          </div>

          <h1 className="text-3xl font-black text-gray-900 mb-4 leading-tight uppercase italic">
            ¡Solicitud <br />
            <span className="text-blue-600">enviada con éxito!</span>
          </h1>
          
          <p className="text-gray-500 text-sm mb-6 leading-relaxed">
            Estamos emocionados de que quieras unirte a la red de clubes <strong>FitControl</strong>.
          </p>

          <div className="bg-gray-50 rounded-2xl p-6 text-left border border-gray-100 mb-8">
            <div className="flex gap-3 mb-4">
              <div className="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <span className="text-blue-600 text-[10px]">1</span>
              </div>
              <p className="text-xs text-gray-600 font-medium">Revisaremos tu información en 24-48h.</p>
            </div>
            <div className="flex gap-3">
              <div className="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <span className="text-blue-600 text-[10px]">2</span>
              </div>
              <p className="text-xs text-gray-600 font-medium">Recibirás un correo para crear tus credenciales de acceso.</p>
            </div>
          </div>

          <Link
            href="/"
            className="block w-full bg-blue-600 text-white py-4 rounded-xl font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/25 active:scale-[0.98]">
            Ir al inicio
          </Link>
        </div>

        <p className="text-gray-400 text-[10px] font-bold uppercase tracking-widest">
          Potenciando el alto rendimiento deportivo
        </p>
      </div>
    </div>
  )
}