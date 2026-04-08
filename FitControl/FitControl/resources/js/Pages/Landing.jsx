import React from "react";
import Hero from "../src/containers/Hero";
import HowWeOperate from "../src/containers/HowWeOperate";
import HowWeWork from "../src/containers/HowWeWork";
import Testimonials from "../src/containers/Testimonials";
import PricingPlans from "../src/containers/PricingPlans";
import ContactUs from "../src/containers/ContactUs";
import Tips from "../src/containers/Tips";
import Questions from "../src/containers/Questions";
import Invitation from "../src/containers/Invitation";
import Footer from "../src/containers/Footer";

export default function Landing() {
    return (
        <React.Fragment>
            <Hero />
            <HowWeOperate />
            <HowWeWork />
            <Testimonials />
            <PricingPlans />
            <ContactUs />
            <Tips />
            <Questions />
            <Invitation />
            <Footer />
        </React.Fragment>
    );
}