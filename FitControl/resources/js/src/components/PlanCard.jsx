import React from "react";

function PlanCard({
  planName,
  planDescription,
  planPrice,
  feature = [],
  designFeatures = [],
  isFeatured,
  discountRate = 0
}) {

  const finalPrice = Math.round(planPrice - (planPrice * discountRate) / 100);

  return (
    <div
      className={`relative w-[300px] p-8 rounded-2xl border transition-all duration-300 hover:scale-105
      ${
        isFeatured
          ? "bg-[#121c4c] text-white border-[#121c4c] shadow-xl"
          : "bg-white text-[#121c4c] border-gray-200"
      }`}
    >

      {isFeatured && (
        <div className="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#1a276b] text-white text-xs px-4 py-1 rounded-full font-semibold">
          Más popular
        </div>
      )}

      <h3 className="text-xl font-bold text-center mb-2">
        {planName}
      </h3>

      <p className="text-sm text-center mb-6 opacity-80">
        {planDescription}
      </p>

      <div className="text-center mb-6">

        {discountRate > 0 && (
          <p className="line-through text-sm opacity-60">
            ${planPrice.toLocaleString()}
          </p>
        )}

        <p className="text-4xl font-bold">
          ${finalPrice.toLocaleString()}
        </p>

        <p className="text-xs mt-1 opacity-70">
          /mes
        </p>

      </div>

      <ul className="flex flex-col gap-2 mb-6">
        {feature.map((f, i) => (
          <li key={i} className="flex items-center gap-2 text-sm">
            <span className="text-[#121c4c]">✔</span>
            {f}
          </li>
        ))}
      </ul>

      <ul className="flex flex-col gap-2 mb-8">
        {designFeatures.map((f, i) => (
          <li key={i} className="flex items-center gap-2 text-sm opacity-80">
            <span className="text-[#121c4c]">✔</span>
            {f}
          </li>
        ))}
      </ul>

      <button
        className={`w-full py-3 rounded-lg font-semibold transition
        ${
          isFeatured
            ? "bg-white text-[#121c4c] hover:bg-gray-100"
            : "bg-[#121c4c] text-white hover:bg-[#1a276b]"
        }`}
      >
        Comenzar
      </button>

    </div>
  );
}

export default PlanCard;
