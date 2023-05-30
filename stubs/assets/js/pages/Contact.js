import React from "react";
import { Head, useForm } from "@inertiajs/react";
import Routing from "fos-router";

export default function ContactPage() {
  const form = useForm({
    name: "",
    email: "",
    message: "",
  });

  const handleInputChange = (e) => {
    form.setData(e.target.id, e.target.value);
  };

  function submit(e) {
    e.preventDefault();
    form.post(Routing.generate("app_contact"), {
      forceFormData: true,
    });
  }

  return (
    <>
      <Head title="Contact Us" />

      <h1>Contact Us</h1>

      <form onSubmit={submit}>
        {form.errors.__non_field__ && (
          <div className="form-error">{form.errors.__non_field__}</div>
        )}

        <div>
          <label htmlFor="name">Name:</label>
          <input
            type="text"
            id="name"
            autoFocus
            value={form.data.name}
            onChange={handleInputChange}
          />
          {form.errors.name && (
            <div className="form-error">{form.errors.name}</div>
          )}
        </div>

        <div>
          <label htmlFor="email">Email:</label>
          <input
            type="email"
            id="email"
            value={form.data.email}
            onChange={handleInputChange}
          />
          {form.errors.email && (
            <div className="form-error">{form.errors.email}</div>
          )}
        </div>

        <div>
          <label htmlFor="message">Message:</label>
          <textarea
            id="message"
            value={form.data.message}
            onChange={handleInputChange}
          ></textarea>
          {form.errors.message && (
            <div className="form-error">{form.errors.message}</div>
          )}
        </div>

        <button type="submit" disabled={form.processing}>
          Send
        </button>
      </form>
    </>
  );
}
