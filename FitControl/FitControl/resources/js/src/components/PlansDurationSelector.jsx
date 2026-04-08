import React from "react";

function PlansDurationSelector({ currentPlanDuration, handleCurrentPlanDurationUpdate }) {
  return (
    <div className="flex gap-3 bg-gray-100 p-2 rounded-xl">

      <button
        onClick={() => handleCurrentPlanDurationUpdate("monthly")}
        className={`px-5 py-2 rounded-lg font-semibold transition ${
          currentPlanDuration === "monthly"
            ? "bg-[#121c4c] text-white shadow"
            : "text-[#121c4c] hover:bg-gray-200"
        }`}
      >
        Mensual
      </button>

      <button
        onClick={() => handleCurrentPlanDurationUpdate("quarterly")}
        className={`px-5 py-2 rounded-lg font-semibold transition ${
          currentPlanDuration === "quarterly"
            ? "bg-[#121c4c] text-white shadow"
            : "text-[#121c4c] hover:bg-gray-200"
        }`}
      >
        Trimestral
      </button>

      <button
        onClick={() => handleCurrentPlanDurationUpdate("yearly")}
        className={`px-5 py-2 rounded-lg font-semibold transition ${
          currentPlanDuration === "yearly"
            ? "bg-[#121c4c] text-white shadow"
            : "text-[#121c4c] hover:bg-gray-200"
        }`}
      >
        Anual
      </button>

    </div>
  );
}

export default PlansDurationSelector;
