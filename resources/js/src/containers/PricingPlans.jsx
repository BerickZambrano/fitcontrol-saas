import React, { useState } from "react";
import { plansDetails } from "../scripts/planDetails";
import PlansDurationSelector from "../components/PlansDurationSelector";
import PlanCard from "../components/PlanCard";

// Percent of Discount
const discountRate = {
  monthly: 0,
  quarterly: 10,
  yearly: 15,
};

function PricingPlans() {
  const [currentPlanDuration, setCurrentPlanDuration] = useState("quarterly");

  const handleCurrentPlanDurationUpdate = (value) => {
    setCurrentPlanDuration(value);
  };

  return (
    <div className="flex justify-center" id="pricing">
      <div className="py-16 px-6 w-full max-w-[1440px] flex flex-col justify-start items-center gap-8">

        {/* Title */}
        <h2 className="text-[#12092a] font-bold text-4xl text-center max-[768px]:text-3xl">
          Planes para hacer crecer tu <span className="text-[#121c4c]">club</span>
        </h2>

        <p className="text-[#12092a] text-center max-w-[700px]">
          Elige el plan que mejor se adapte a tu club. Empieza gratis y escala
          a medida que crece tu equipo con FitControl.
        </p>

        {/* Duration Selector */}
        <PlansDurationSelector
          currentPlanDuration={currentPlanDuration}
          handleCurrentPlanDurationUpdate={handleCurrentPlanDurationUpdate}
        />

        {/* Plans Container */}
        <div className="flex gap-7 flex-wrap justify-center items-center mt-6">
          {plansDetails.map((data) => (
            <PlanCard
              key={data.planName}
              {...data}
              discountRate={discountRate[currentPlanDuration]}
            />
          ))}
        </div>

      </div>
    </div>
  );
}

export default PricingPlans;