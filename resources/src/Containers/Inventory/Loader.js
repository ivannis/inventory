import React from "react"
import ContentLoader from "react-content-loader"

const Loader = (props) => (
    <ContentLoader
        width={755}
        height={120}
        viewBox="0 0 755 120"
        backgroundColor="#f0f0f0"
        foregroundColor="#dedede"
        {...props}
    >
        <rect x="0" y="0" rx="10" ry="10" width="755" height="10" />
        <rect x="0" y="30" rx="10" ry="10" width="755" height="10" />
        <rect x="0" y="60" rx="10" ry="10" width="755" height="10" />
        <rect x="0" y="90" rx="10" ry="10" width="755" height="10" />
        <rect x="0" y="120" rx="10" ry="10" width="755" height="10" />
    </ContentLoader>
)

export default Loader

