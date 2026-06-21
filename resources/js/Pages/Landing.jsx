import React, { useState, useEffect } from "react";
import Navbar from "../src/landing/components/Navbar";
import HeroSection from "../src/landing/components/HeroSection";
import StatsBar from "../src/landing/components/StatsBar";
import ProblemSection from "../src/landing/components/ProblemSection";
import FeaturesSection from "../src/landing/components/FeaturesSection";
import ScreenshotsSection from "../src/landing/components/ScreenshotsSection";
import BenefitsSection from "../src/landing/components/BenefitsSection";
import PricingSection from "../src/landing/components/PricingSection";
import TestimonialsSection from "../src/landing/components/TestimonialsSection";
import CTASection from "../src/landing/components/CTASection";
import Footer from "../src/landing/components/Footer";
import SplashOverlay from "../src/components/SplashOverlay";

export default function Landing() {
    const [splashState, setSplashState] = useState({ isVisible: false, targetUrl: "" });

    const navigateWithSplash = (url) => {
        setSplashState({ isVisible: true, targetUrl: url });
    };

    useEffect(() => {
        const handlePageShow = () => {
            setSplashState({ isVisible: false, targetUrl: "" });
        };

        window.addEventListener("pageshow", handlePageShow);
        return () => window.removeEventListener("pageshow", handlePageShow);
    }, []);

    return (
        <>
            <Navbar navigateWithSplash={navigateWithSplash} />
            <HeroSection navigateWithSplash={navigateWithSplash} />
            <StatsBar />
            <ProblemSection />
            <FeaturesSection />
            <ScreenshotsSection />
            <BenefitsSection />
            <PricingSection navigateWithSplash={navigateWithSplash} />
            <TestimonialsSection />
            <CTASection navigateWithSplash={navigateWithSplash} />
            <Footer navigateWithSplash={navigateWithSplash} />
            <SplashOverlay isVisible={splashState.isVisible} targetUrl={splashState.targetUrl} />
        </>
    );
}