import React from "react"
import ContentLoader from "react-content-loader"

const Loader = (props) => (
    <ContentLoader
        width={755}
        height={184}
        viewBox="0 0 755 184"
        backgroundColor="#f0f0f0"
        foregroundColor="#dedede"
        {...props}
    >
        <rect x="0" y="0" rx="10" ry="10" width="755" height="184" />
    </ContentLoader>
)

export default Loader

