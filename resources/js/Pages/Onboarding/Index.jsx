import { useState } from 'react'
import { router } from '@inertiajs/react'
import StepClub from '../../src/components/onboarding/StepClub'
import StepDocumentos from '../../src/components/onboarding/StepDocumentos'
import StepPlan from '../../src/components/onboarding/StepPlan'
import SplashOverlay from '../../src/components/SplashOverlay'

const STEPS = ['Datos del club', 'Documentos', 'Plan']

// Campos que pertenecen a cada paso (para saltar al paso correcto si hay error)
const STEP_FIELDS = [
  ['nombre', 'nombre_corto', 'ciudad', 'pais', 'direccion', 'telefono', 'escudo_url', 'tipo_club'],
  ['nit', 'encargado_nombre', 'encargado_email', 'encargado_telefono', 'rut_document', 'camara_comercio'],
  ['plan'],
]

export default function OnboardingIndex() {
  const [step, setStep] = useState(0)
  const [loading, setLoading] = useState(false)
  const [errors, setErrors] = useState({})
  const [globalError, setGlobalError] = useState('')

  const [formData, setFormData] = useState({
    nombre: '',
    nombre_corto: '',
    tipo_club: '',
    nit: '',
    telefono: '',
    direccion: '',
    ciudad: '',
    pais: 'Colombia',
    encargado_nombre: '',
    encargado_email: '',
    encargado_telefono: '',
    escudo_url: null,
    rut_document: null,
    camara_comercio: null,
    plan: '',
  })

  const updateData = (fields) => setFormData(prev => ({ ...prev, ...fields }))
  const next = () => setStep(s => s + 1)
  const back = () => setStep(s => s - 1)

  const submit = () => {
    setLoading(true)
    setGlobalError('')
    router.post('/onboarding', formData, {
      forceFormData: true,
      onSuccess: () => router.visit('/onboarding/success'),
      onError: (errs) => {
        setErrors(errs)
        setLoading(false)
        // Determinar a qué paso ir según el primer campo con error
        const errorFields = Object.keys(errs)
        for (let i = 0; i < STEP_FIELDS.length; i++) {
          if (errorFields.some(f => STEP_FIELDS[i].includes(f))) {
            setStep(i)
            break
          }
        }
        setGlobalError('Por favor, revisa los campos marcados en rojo antes de continuar.')
      },
      onFinish: () => setLoading(false),
    })
  }

  return (
    <div className="min-h-screen grid lg:grid-cols-2 font-['Outfit',_sans-serif]">
      {/* Left Side: Image & Branding */}
      <div className="hidden lg:flex relative items-center justify-center bg-gray-900 overflow-hidden">
        <img
          src="/images/auth-bg.png"
          alt="FitControl Training"
          className="absolute inset-0 w-full h-full object-cover opacity-40 scale-105"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />

        <div className="relative z-10 p-12 max-w-xl">

          <h2 className="text-5xl font-black text-white leading-tight mb-6 uppercase italic">
            El siguiente nivel <br />
            <span className="text-blue-500">empieza aquí.</span>
          </h2>
          <p className="text-gray-300 text-lg">
            Únete a la plataforma líder en gestión deportiva y potencia el rendimiento de tu club desde hoy mismo.
          </p>
        </div>
      </div>

      {/* Right Side: Form Content */}
      <div className="flex flex-col items-center justify-center py-12 px-6 bg-white overflow-y-auto">
        <div className="w-full max-w-md">
          {/* Mobile Branding */}
          <div className="lg:hidden flex items-center justify-center mb-12">
            <img src="/images/logo.png" alt="FitControl" className="h-10 w-auto object-contain" />
          </div>

          <div className="mb-10">
            <h1 className="text-3xl font-bold text-gray-900">Bienvenido al club</h1>
            <p className="text-gray-500 mt-2">Configura tu acceso en solo 3 pasos</p>
          </div>

          <div className="flex items-center gap-3 mb-10 overflow-x-auto pb-2 scrollbar-hide">
            {STEPS.map((label, i) => (
              <div key={i} className="flex items-center gap-3 flex-shrink-0">
                <div className={`flex items-center justify-center w-9 h-9 rounded-xl text-sm font-bold transition-all duration-300
                  ${i < step ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : i === step ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-gray-100 text-gray-400'}`}>
                  {i < step ? '✓' : i + 1}
                </div>
                <span className={`text-xs font-bold uppercase tracking-wider ${i === step ? 'text-gray-900' : 'text-gray-400'}`}>
                  {label}
                </span>
                {i < STEPS.length - 1 && (
                  <div className={`w-6 h-0.5 rounded-full ${i < step ? 'bg-blue-600' : 'bg-gray-100'}`} />
                )}
              </div>
            ))}
          </div>

          <div className="relative">
            {globalError && (
              <div className="mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-center gap-3">
                <span className="text-red-500 text-lg">⚠</span>
                <p className="text-red-600 text-xs font-bold">{globalError}</p>
              </div>
            )}
            {step === 0 && <StepClub data={formData} onChange={updateData} onNext={next} errors={errors} />}
            {step === 1 && <StepDocumentos data={formData} onChange={updateData} onNext={next} onBack={back} errors={errors} />}
            {step === 2 && <StepPlan data={formData} onChange={updateData} onBack={back} onSubmit={submit} loading={loading} errors={errors} />}
          </div>

          <p className="text-gray-400 text-sm mt-10 text-center">
            ¿Ya tienes cuenta?{' '}
            <button onClick={() => window.location.href = '/login'} className="text-blue-600 font-bold hover:underline cursor-pointer bg-transparent border-none p-0 font-inherit">Inicia sesión</button>
          </p>
          <p className="text-gray-400 text-sm mt-10 text-center">
            Volver{' '}
            <button onClick={() => window.location.href = '/'} className="text-blue-600 font-bold hover:underline cursor-pointer bg-transparent border-none p-0 font-inherit">al inicio</button>
          </p>
        </div>
      </div>
    </div>
  )
}