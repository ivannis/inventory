import React from "react"
import ContentLoader from "react-content-loader"

const Loader = (props) => (
    <ContentLoader
        speed={2}
        width={90}
        height={150}
        viewBox="0 0 160 250"
        backgroundColor="#f3f3f3"
        foregroundColor="#ecebeb"
        {...props}
    >
        <rect x="0" y="0" rx="5" ry="5" width="160" height="10" />
        <rect x="0" y="30" rx="5" ry="5" width="160" height="10" />
        <rect x="0" y="60" rx="5" ry="5" width="160" height="10" />
        <rect x="0" y="90" rx="5" ry="5" width="160" height="10" />
    </ContentLoader>
)

export default Loader

